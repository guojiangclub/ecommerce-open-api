<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Repositories;

use GuoJiangClub\EC\Open\Backend\Store\Model\Goods;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use GuoJiangClub\EC\Open\Backend\Store\Model\Product;
use GuoJiangClub\EC\Open\Backend\Store\Model\Category;
use Maatwebsite\Excel\Facades\Excel;
use DB;

/**
 * Class ProductRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ProductRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Product::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * 购物车，根据商品ID，选择的商品规格json值获取货品数据
     * @param $goods_id
     * @param $specJSON
     * @return mixed
     */
    public function getProduct($goods_id, $specJSON)
    {
        $jsonData = json_decode($specJSON, true);
        if (!$jsonData) {
            return false;
        }
        //获取货品数据       
        $product_info = $this->with('photo')->findWhere(['goods_id' => $goods_id, 'spec_array' => $specJSON, ['store_nums', '>', 0]])->first();
        //匹配到货品数据
        if (!$product_info) {
            return false;
        }
        return $product_info;

    }

    /**
     * 根据货品ID获取对应的商品
     * @param $product_id
     * @return mixed
     */
    public function getGoodsInfo($product_id)
    {
        $product = $this->findWhere(['id' => $product_id])->first();
        if ($product) return $product;
        return false;
    }


    /**
     * 根据货品SKU获取对应的商品
     * @param $sku
     * @return mixed
     */
    public function getGoodsInfoBySku($sku)
    {
        $product = $this->findWhere(['sku' => $sku])->first();
        if ($product) return $product;
        return false;
    }

    /**获取产品信息数据
     * @param $where
     * @param int $limit
     * @return mixed
     */
    public function getProductPaginated($where, $limit = 50, $sku_price = '')
    {
        $date = $this->scopeQuery(function ($query) use ($where, $sku_price) {
            if (is_array($where) AND count($where) > 0) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            if ($sku_price == 'sell_price' OR $sku_price == 'market_price') {
                $query = $query->where($sku_price, '>=', request('price_begin'));
                $query = $query->where($sku_price, '<=', request('price_end'));
            }

            return $query->orderBy('updated_at', 'desc');

        });

        if ($limit == 0) {
            return $date->all();
        } else {
            return $date->paginate($limit);
        }

    }


    /**
     * 获取第一个有库存的货品
     * @param $goodsID
     * @return mixed
     */
    public function getHaveStockProduct($goodsID)
    {
        $products = $this->with('photo')->findWhere(['goods_id' => $goodsID]);
        foreach ($products as $key => $val) {
            if ($val->store_nums > 0) {
                return $val;
            }
        }

        return false;
    }

    /**
     * 获取无库存的货品
     * @param $specStr
     * @param $goodsID
     * @return array
     */
    public function getNullStockProduct($specStr, $goodsID)
    {
        $products = $this->findWhere(['goods_id' => $goodsID]);
        $Stock = [
            'nullStock' => [],
            'haveStock' => []
        ];

        foreach ($products as $key => $val) {
            foreach ($specStr as $value) {
                if (strpos($val->spec_array, $value) >= 0) {

                    $spec = rtrim(str_replace($value, '', $val->spec_array), ']');
                    $spec = ltrim($spec, '[');

                    if (substr($spec, -1, 1) == ',') {
                        $spec = rtrim($spec, ',');
                    } elseif (substr($spec, 0, 1) == ',') {
                        $spec = ltrim($spec, ',');
                    }

                    if ($val->store_nums == 0) {
                        $Stock['nullStock'][] = $spec;
                    } else {
                        $Stock['haveStock'][] = $spec;
                    }
                }

            }

        }

        return $Stock;
    }


    /**
     * 根据SKU查询货品
     * @param $sku
     * @return mixed
     */
    public function getProductBySku($sku)
    {
        return $this->findWhere(['sku' => $sku])->first();
    }

    public function getExcelGoods($limit = 50)
    {
        $goodsTable = config('ibrand.app.database.prefix', 'ibrand_') . 'goods';
        $productTable = config('ibrand.app.database.prefix', 'ibrand_') . 'goods_product';
        $modelTable = config('ibrand.app.database.prefix', 'ibrand_') . 'goods_model';
        $products = Product::join($goodsTable, $goodsTable . '.id', '=', $productTable . '.goods_id')
            ->join($modelTable, $modelTable . '.id', '=', $goodsTable . '.model_id')
            ->select($goodsTable . '.id',
                $productTable . '.sku',
                $goodsTable . '.goods_no',
                $goodsTable . '.name',
                $modelTable . '.name as type',
                $goodsTable . '.market_price',
                $goodsTable . '.sell_price',
                $productTable . '.market_price as sku_market_price',
                $productTable . '.sell_price as sku_sell_price',
                $goodsTable . '.is_del',
                $productTable . '.store_nums',
                $goodsTable . '.tags',
                $productTable . '.spec_ids as spec1'
            );
        $view = !empty(request('view')) ? request('view') : 0;
        $products = $products->where($goodsTable . '.is_del', $view);

        if (is_array($ids = request('ids'))) {
            $products = $products->whereIn($goodsTable . '.id', $ids);
        }

        if (!empty(request('field')) && !empty(request('value'))) {
            if (request('field') == 'sku') {
                $products = $products->where($productTable . '.sku', 'like', '%' . request('value') . '%');
            } elseif (request('field') == 'category') {
                $goodsIds = $this->categoryGetGoodsIds(request('value'));
                $products = $products->whereIn($goodsTable . '.id', $goodsIds);
            } else {
                $products = $products->where($goodsTable . '.' . request('field'), 'like', '%' . request('value') . '%');
            }
        }
        if (!empty(request('store_begin'))) {
            $products = $products->where($goodsTable . '.store_nums', '>=', request('store_begin'));
        }
        if (!empty(request('store_end'))) {
            $products = $products->where($goodsTable . '.store_nums', '<=', request('store_end'));
        }
        if (!empty(request('price_begin'))) {
            if (request('price') == 'sku_sell_price' OR request('price') == 'sku_market_price') {
                $column = request('price') == 'sku_sell_price' ? 'sell_price' : 'market_price';
                $products = $products->where($productTable . '.' . $column, '>=', request('price_begin'));
            } else {
                $products = $products->where($goodsTable . '.' . request('price'), '>=', request('price_begin'));
            }
        }

        if (!empty(request('price_end'))) {
            if (request('price') == 'sku_sell_price' OR request('price') == 'sku_market_price') {
                $column = request('price') == 'sku_sell_price' ? 'sell_price' : 'market_price';
                $products = $products->where($productTable . '.' . $column, '<=', request('price_end'));
            } else {
                $products = $products->where($goodsTable . '.' . request('price'), '<=', request('price_end'));
            }
        }

        $products = $products->orderBy($goodsTable . '.id')->paginate($limit);
        $lastPage = $products->lastPage();
        $products = $products->items();

        $goodsSpecTable = config('ibrand.app.database.prefix', 'ibrand_') . 'goods_spec';
        $goodsSpecValueTable = config('ibrand.app.database.prefix', 'ibrand_') . 'goods_spec_value';
        $specs = DB::table($goodsSpecValueTable)->join($goodsSpecTable, $goodsSpecTable . '.id', '=', $goodsSpecValueTable . '.spec_id')
            ->select($goodsSpecValueTable . '.id as id', $goodsSpecValueTable . '.spec_id as spec_id', $goodsSpecTable . '.name as spec', $goodsSpecValueTable . '.name as value')
            ->get();


        $goodsSpecRelationTable = config('ibrand.app.database.prefix', 'ibrand_') . 'goods_spec_relation';
        foreach ($products as $key => &$product) {
            $product = $product->toArray();
            $products[$key]['sku_market_price'] = !$product['sku_market_price'] ? $product['market_price'] : $product['sku_market_price'];

            $specIds = $product['spec1'];
            $specIds = json_decode($specIds, true);
            $products[$key]['spec1'] = '';
            $products[$key]['spec2'] = '';
            $products[$key]['spec3'] = '';
            if (is_array($specIds)) {
                foreach ($specIds as $id) {
                    $spec = $specs->where('id', $id)->first();
                    if ($spec) {
                        if ($spec->spec != 2) {
                            $products[$key]['spec1'] = $spec->value;
                        }
                        if ($spec->spec_id == 2) {
                            $products[$key]['spec2'] = $spec->value;
                        }

                        if ($spec->spec_id == 2) {
                            $alias = DB::table($goodsSpecRelationTable)->where('spec_value_id', $spec->id)->where('goods_id', $product['id'])->first();
                            $products[$key]['spec3'] = isset($alias->alias) ? $alias->alias : '';
                        }


                    }
                }
            }
            $goods = Goods::find($product['id']);
            $cate = '';
            $categories = $goods->categories;
            foreach ($categories as $category) {
                $cate .= $category->name . ',';
            }
            $products[$key]['cate'] = $cate;
        }
        return [
            'products' => $products,
            'lastPage' => $lastPage
        ];
    }

    public function getProductExcel($products, $name = 'goods_data_')
    {


        $title = ['商品ID', 'SKU', '商品编号', '商品名称', '类型', '销售价', '吊牌价', '上架', '库存', '标签', '尺码', '颜色', '分类', '参数'];
        $count = count($products);
        $excel = Excel::create($name, function ($excel) use ($products, $title, $count) {

            $excel->sheet('商品列表', function ($sheet) use ($products, $title, $count) {

                $sheet->prependRow(1, $title);
                $sheet->rows($products);
                $sheet->setColumnFormat(array(
                    'A2:A' . ($count + 1) => '0',
                    'B2:B' . ($count + 1) => '0',
                    'C2:B' . ($count + 1) => '0'
                ));
                $sheet->setWidth(array(
                    'A' => 5,
                    'B' => 20,
                    'C' => 10,
                    'D' => 40,
                    'E' => 5,
                    'F' => 10,
                    'G' => 10,
                    'H' => 5,
                    'I' => 5,
                    'J' => 20,
                    'K' => 10,
                    'L' => 30,
                    'M' => 80,
                    'N' => 500,
                ));

            });
        })->store('xls');

        return "$name.xls";
    }

    private function categoryGetGoodsIds($category)
    {
        $categoryIds = Category::where('name', 'like', '%' . $category . '%')->pluck('id')->toArray();
        $goods = Goods::with('categories')->get();
        return $goods->filter(function ($value, $key) use ($categoryIds) {
            foreach ($value->categories as $category) {
                if (in_array($category->id, $categoryIds))
                    return true;
            }
            return false;
        })->pluck('id')->toArray();
    }

}

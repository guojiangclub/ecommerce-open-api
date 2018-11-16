<?php
namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use ElementVip\Component\Bundle\Repository\BundleRepository;
use iBrand\EC\Open\Backend\Store\Model\Goods;
use iBrand\EC\Open\Backend\Store\Repositories\GoodsRepository;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    private $bundle;
    private $goodsRepository;

    public function __construct(
        BundleRepository $bundleRepository,
        GoodsRepository $goodsRepository)
    {
        $this->bundle = $bundleRepository;
        $this->goodsRepository = $goodsRepository;
    }

    public function index()
    {
        $bundle = $this->bundle->paginate(15);
        return view('store-backend::promotion.bundle', compact('bundle'));
    }

    public function create()
    {
        return view('store-backend::promotion.bundle_create');
    }

    public function store(Request $input)
    {
        $input = $input->toArray();
        $res = $this->bundle->add($input);
        return response()->json([
            'status' => $res
        ]);
    }

    public function edit($id)
    {
        $bundle = $this->bundle->find($id);
        return view('store-backend::promotion.bundle_edit', compact('bundle'));
    }

    public function update(Request $input)
    {
        $input = $input->toArray();
        $res = $this->bundle->updateBundle($input);
        return response()->json([
            'status' => $res,
        ]);
    }

    public function delete($id)
    {
        $this->bundle->delete($id);
        return redirect()->back()->withFlashSuccess('套餐已删除');
    }

    public function getSpu()
    {
        $goods = Goods::paginate(15);
        return view('store-backend::promotion.includes.bundleGetSpu', compact('goods'));
    }

    public function getSpuData()
    {
        $goods = Goods::paginate(15);

        if (!empty(request('title'))) {
            $where['name'] = ['like', '%' . request('title') . '%'];
            $goods = $this->goodsRepository->getGoodsPaginated($where);
        }

        return view('store-backend::promotion.includes.bundleGetSpuPost', compact('goods'));
    }

}
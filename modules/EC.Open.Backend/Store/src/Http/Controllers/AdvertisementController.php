<?php

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use iBrand\EC\Open\Backend\Store\Model\Advertisement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use iBrand\EC\Open\Backend\Store\Repositories\AdvertisementRepository;
use iBrand\EC\Open\Backend\Store\Repositories\AdvertisementItemRepository;


class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $advertisementRepository;
    protected $advertisementItemRepository;

    public function __construct(AdvertisementRepository $advertisementRepository
    ,AdvertisementItemRepository $advertisementItemRepository
    )
    {
        $this->advertisementRepository =$advertisementRepository;
        $this->advertisementItemRepository =$advertisementItemRepository;
    }
    public function index()
    {
        $advertisement=$this->advertisementRepository->orderBy('updated_at','desc')->paginate(20);

        return view("store-backend::advertisement.ad.index",compact('advertisement'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('store-backend::advertisement.ad.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except('id','_token','file');
        if(request('id'))
        {
            $this->advertisementRepository->update($input,request('id'));
            $id=request('id');
        }else{
            $ad=$this->advertisementRepository->create($input);
            $id=$ad->id;
        }

        return response()->json(['status' => true
            , 'error_code' => 0
            , 'error' => ''
            , 'data' => $id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $advertisement_list=$this->advertisementRepository->findByField('id',$id)->first();
        return view('store-backend::advertisement.ad.edit',compact('advertisement_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $adItem=$this->advertisementItemRepository->findByField('ad_id',$id)->first();
        if(is_null($adItem)){
            $this->advertisementRepository->delete($id);
            return redirect()->back();
        }

        return redirect()->back()->with('message','推广位下非空删除失败');
    }

    public function toggleStatus(Request $request)
    {
        $status=request('status');
        $id=request('aid');
        $user = Advertisement::find($id);
        $user->fill($request->only('status'));
        $user->save();
        return response()->json(
            ['status' => true
                , 'code' => 200
                , 'message' =>''
                , 'data' =>[]]
        );
    }

}

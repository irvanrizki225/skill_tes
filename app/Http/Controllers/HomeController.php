<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hasil = DB::select(DB::raw("SELECT * FROM `report_product`"));
        $brand = DB::select(DB::raw("SELECT * FROM `product_brand`"));
        $area = DB::select(DB::raw("SELECT * FROM `store_area`"));

        // SELECT * FROM `report_product` 
        // LEFT JOIN store ON report_product.store_id = store.store_id 
        // RIGHT JOIN store_area ON store.store_id = store_area.area_id 
        // LEFT JOIN product ON report_product.product_id = product.product_id 
        // RIGHT JOIN product_brand ON product.brand_id = product_brand.brand_id 
        // WHERE store_area.area_name = 'DKI jakarta' AND report_product.compliance=1;

        foreach ($area as $key => $value) {
            $total_row = DB::select(DB::raw("SELECT * FROM `report_product` 
            LEFT JOIN store ON report_product.store_id = store.store_id 
            RIGHT JOIN store_area ON store.store_id = store_area.area_id 
            WHERE store_area.area_name = '$value->area_name'"));

            $complaint = DB::select(DB::raw("SELECT SUM(report_product.compliance) AS sum_compliance FROM `report_product` 
            LEFT JOIN store ON report_product.store_id = store.store_id 
            RIGHT JOIN store_area ON store.store_id = store_area.area_id 
            WHERE store_area.area_name = '$value->area_name'"));

            $persentase[] = $complaint[0]->sum_compliance/count($total_row)*100;
        }

        

        // foreach ($brand as $key => $value) {

        //     $persentase_jakarta[] = [
        //         $value->brand_name => $complaint_jakarta[0]->compliance_jakarta / $total_row[0]->count * 100,
        //     ];

        // }

        return view('home', compact('hasil', 'persentase','brand','area'));

    }

    public function cari()
    {
        $hasil = DB::select(DB::raw("SELECT * FROM `report_product` 
        LEFT JOIN store ON report_product.store_id = store.store_id 
        RIGHT JOIN store_area ON store.store_id = store_area.area_id;"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }
}

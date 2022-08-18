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
        $brand = DB::select(DB::raw("SELECT * FROM `product_brand`"));
        $area = DB::select(DB::raw("SELECT * FROM `store_area`"));

        $all = DB::select(DB::raw("SELECT * FROM `report_product` 
        LEFT JOIN store ON report_product.store_id = store.store_id 
        RIGHT JOIN store_area ON store.store_id = store_area.area_id 
        LEFT JOIN product ON report_product.product_id = product.product_id 
        RIGHT JOIN product_brand ON product.brand_id = product_brand.brand_id 
        WHERE store_area.area_name = 'DKI jakarta' AND report_product.compliance=1 "));

        foreach ($brand as $key => $value) {
            $brands[] = $value->brand_name; 
        }
        
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


            $total_row_roti_tawar[] = DB::select(DB::raw("SELECT * FROM `report_product` 
            LEFT JOIN store ON report_product.store_id = store.store_id 
            RIGHT JOIN store_area ON store.store_id = store_area.area_id 
            LEFT JOIN product ON report_product.product_id = product.product_id 
            RIGHT JOIN product_brand ON product.brand_id = product_brand.brand_id 
            WHERE store_area.area_name = '$value->area_name' AND product_brand.brand_name='$brands[0]'"));

            $total_row_susu_kaleng[] = DB::select(DB::raw("SELECT * FROM `report_product` 
            LEFT JOIN store ON report_product.store_id = store.store_id 
            RIGHT JOIN store_area ON store.store_id = store_area.area_id 
            LEFT JOIN product ON report_product.product_id = product.product_id 
            RIGHT JOIN product_brand ON product.brand_id = product_brand.brand_id 
            WHERE store_area.area_name = '$value->area_name' AND product_brand.brand_name='$brands[1]'"));
            
            $roti_tawar[] = DB::select(DB::raw("SELECT SUM(report_product.compliance) AS sum_compliance , store_area.area_name 
            FROM `report_product` LEFT JOIN store ON report_product.store_id = store.store_id 
            RIGHT JOIN store_area ON store.store_id = store_area.area_id 
            LEFT JOIN product ON report_product.product_id = product.product_id 
            RIGHT JOIN product_brand ON product.brand_id = product_brand.brand_id 
            WHERE store_area.area_name = '$value->area_name' AND product_brand.brand_name='$brands[1]'"));

            $susu_kaleng[] = DB::select(DB::raw("SELECT SUM(report_product.compliance) AS sum_compliance , store_area.area_name 
            FROM `report_product` LEFT JOIN store ON report_product.store_id = store.store_id 
            RIGHT JOIN store_area ON store.store_id = store_area.area_id 
            LEFT JOIN product ON report_product.product_id = product.product_id 
            RIGHT JOIN product_brand ON product.brand_id = product_brand.brand_id 
            WHERE store_area.area_name = '$value->area_name' AND product_brand.brand_name='$brands[1]'"));
            
        }

        for ($i=0; $i < count($roti_tawar); $i++) { 
            $persentase_roti_tawar[] = $roti_tawar[$i][0]->sum_compliance/count($total_row_roti_tawar[$i])*100;
            $persentase_susu_kaleng[] = $susu_kaleng[$i][0]->sum_compliance/count($total_row_susu_kaleng[$i])*100;
        }
        $persentase_brand[] = [
            0 => [
                'brand' => $brands[0],
                $area[0]->area_name => round($persentase_roti_tawar[0],1),
                $area[1]->area_name => round($persentase_roti_tawar[1],1),
                $area[2]->area_name => round($persentase_roti_tawar[2],1),
                $area[3]->area_name => round($persentase_roti_tawar[3],1),
                $area[4]->area_name => round($persentase_roti_tawar[4],1),
                ],
            1 => [
                'brand' => $brands[1],
                $area[0]->area_name => round($persentase_susu_kaleng[0],1),
                $area[1]->area_name => round($persentase_susu_kaleng[1],1),
                $area[2]->area_name => round($persentase_susu_kaleng[2],1),
                $area[3]->area_name => round($persentase_susu_kaleng[3],1),
                $area[4]->area_name => round($persentase_susu_kaleng[4],1),
                ],
    
        ];

        return view('home', compact( 'persentase','brand','area','roti_tawar', 'susu_kaleng','brands', 'persentase_brand'));

    }

    public function cari(Request $request)
    {
        $areaR[] = $request->area;
        $from = $request->from;
        $to = $request->to;

        $brand = DB::select(DB::raw("SELECT * FROM `product_brand`"));
        $area = DB::select(DB::raw("SELECT * FROM `store_area`"));


        foreach ($brand as $key => $value) {
            $brands[] = $value->brand_name; 
        }
        foreach ($areaR as $key => $value) {
            $total_row = DB::select(DB::raw("SELECT * FROM `report_product` 
            LEFT JOIN store ON report_product.store_id = store.store_id 
            RIGHT JOIN store_area ON store.store_id = store_area.area_id 
            WHERE store_area.area_name = '$value' AND report_product.tanggal BETWEEN '$from' AND '$to';"));

            $complaint = DB::select(DB::raw("SELECT SUM(report_product.compliance) AS sum_compliance FROM `report_product` 
            LEFT JOIN store ON report_product.store_id = store.store_id 
            RIGHT JOIN store_area ON store.store_id = store_area.area_id 
            WHERE store_area.area_name = '$value' AND report_product.tanggal BETWEEN '$from' AND '$to'"));
            
            if ($areaR[0] == 'DKI Jakarta') {
                $persentase[] = array(
                    round($complaint[0]->sum_compliance/count($total_row)*100),0,0,0,0
                );
            }elseif ($areaR[0] == 'Jawa Barat') {
                $persentase[] = array(
                    0,round($complaint[0]->sum_compliance/count($total_row)*100),0,0,0
                );
            }elseif ($areaR[0] == 'Kalimantan') {
                $persentase[] = array(
                    0,0,round($complaint[0]->sum_compliance/count($total_row)*100),0,0
                );
            }elseif ($areaR[0] == 'Jawa Tengah') {
                $persentase[] = array(
                    0,0,0,round($complaint[0]->sum_compliance/count($total_row)*100),0
                );
            }else {
                $persentase[] = array(
                    0,0,0,0,round($complaint[0]->sum_compliance/count($total_row)*100)
                );
            }


            $total_row_roti_tawar[] = DB::select(DB::raw("SELECT * FROM `report_product` 
            LEFT JOIN store ON report_product.store_id = store.store_id 
            RIGHT JOIN store_area ON store.store_id = store_area.area_id 
            LEFT JOIN product ON report_product.product_id = product.product_id 
            RIGHT JOIN product_brand ON product.brand_id = product_brand.brand_id 
            WHERE store_area.area_name = '$value' AND product_brand.brand_name='$brands[0]' AND report_product.tanggal BETWEEN '$from' AND '$to'"));

            $total_row_susu_kaleng[] = DB::select(DB::raw("SELECT * FROM `report_product` 
            LEFT JOIN store ON report_product.store_id = store.store_id 
            RIGHT JOIN store_area ON store.store_id = store_area.area_id 
            LEFT JOIN product ON report_product.product_id = product.product_id 
            RIGHT JOIN product_brand ON product.brand_id = product_brand.brand_id 
            WHERE store_area.area_name = '$value' AND product_brand.brand_name='$brands[1]' AND report_product.tanggal BETWEEN '$from' AND '$to'"));
            
            $roti_tawar[] = DB::select(DB::raw("SELECT SUM(report_product.compliance) AS sum_compliance , store_area.area_name 
            FROM `report_product` LEFT JOIN store ON report_product.store_id = store.store_id 
            RIGHT JOIN store_area ON store.store_id = store_area.area_id 
            LEFT JOIN product ON report_product.product_id = product.product_id 
            RIGHT JOIN product_brand ON product.brand_id = product_brand.brand_id 
            WHERE store_area.area_name = '$value' AND product_brand.brand_name='$brands[1]' AND report_product.tanggal BETWEEN '$from' AND '$to'"));

            $susu_kaleng[] = DB::select(DB::raw("SELECT SUM(report_product.compliance) AS sum_compliance , store_area.area_name 
            FROM `report_product` LEFT JOIN store ON report_product.store_id = store.store_id 
            RIGHT JOIN store_area ON store.store_id = store_area.area_id 
            LEFT JOIN product ON report_product.product_id = product.product_id 
            RIGHT JOIN product_brand ON product.brand_id = product_brand.brand_id 
            WHERE store_area.area_name = '$value' AND product_brand.brand_name='$brands[1]' AND report_product.tanggal BETWEEN '$from' AND '$to'"));
            
        }

        for ($i=0; $i < count($roti_tawar); $i++) { 
            $persentase_roti_tawar[] = $roti_tawar[$i][0]->sum_compliance/count($total_row_roti_tawar[$i])*100;
            $persentase_susu_kaleng[] = $susu_kaleng[$i][0]->sum_compliance/count($total_row_susu_kaleng[$i])*100;
        }
        // dd($areaR) ;
        if ($areaR[0] == 'DKI jakarta') {
            $persentase_brand[] = [
                0 => [
                    'brand' => $brands[0],
                    'DKI Jakarta' => round($persentase_roti_tawar[0],1),
                    'Jawa Barat' => 0,
                    'Kalimantan' => 0,
                    'Jawa Tengah' => 0,
                    'Bali' => 0,
                    ],
                1 => [
                    'brand' => $brands[1],
                    'DKI Jakarta' => round($persentase_roti_tawar[0],1),
                    'Jawa Barat' => 0,
                    'Kalimantan' => 0,
                    'Jawa Tengah' => 0,
                    'Bali' => 0,
                    ],
        
            ];
        }elseif ($areaR[0] == 'Jawa Barat') {
            $persentase_brand[] = [
                0 => [
                    'brand' => $brands[0],
                    'DKI Jakarta' => 0,
                    'Jawa Barat' => round($persentase_roti_tawar[0],1),
                    'Kalimantan' => 0,
                    'Jawa Tengah' => 0,
                    'Bali' => 0,
                    ],
                1 => [
                    'brand' => $brands[1],
                    'DKI Jakarta' => 0,
                    'Jawa Barat' => round($persentase_roti_tawar[0],1),
                    'Kalimantan' => 0,
                    'Jawa Tengah' => 0,
                    'Bali' => 0,
                    ],
        
            ];
        }elseif ($areaR[0] == 'Kalimantan') {
            $persentase_brand[] = [
                0 => [
                    'brand' => $brands[0],
                    'DKI Jakarta' => 0,
                    'Jawa Barat' => 0,
                    'Kalimantan' => round($persentase_roti_tawar[0],1),
                    'Jawa Tengah' => 0,
                    'Bali' => 0,
                    ],
                1 => [
                    'brand' => $brands[1],
                    'DKI Jakarta' => 0,
                    'Jawa Barat' => 0,
                    'Kalimantan' => round($persentase_roti_tawar[0],1),
                    'Jawa Tengah' => 0,
                    'Bali' => 0,
                    ],
        
            ];
        }elseif ($areaR[0] == 'Jawa Tengah') {
            $persentase_brand[] = [
                0 => [
                    'brand' => $brands[0],
                    'DKI Jakarta' => 0,
                    'Jawa Barat' => 0,
                    'Kalimantan' => 0,
                    'Jawa Tengah' => round($persentase_roti_tawar[0],1),
                    'Bali' => 0,
                    ],
                1 => [
                    'brand' => $brands[1],
                    'DKI Jakarta' => 0,
                    'Jawa Barat' => 0,
                    'Kalimantan' => 0,
                    'Jawa Tengah' => round($persentase_roti_tawar[0],1),
                    'Bali' => 0,
                    ],
        
            ];
        }else{
            $persentase_brand[] = [
                0 => [
                    'brand' => $brands[0],
                    'DKI Jakarta' => 0,
                    'Jawa Barat' => 0,
                    'Kalimantan' => 0,
                    'Jawa Tengah' => 0,
                    'Bali' => round($persentase_roti_tawar[0],1),
                    ],
                1 => [
                    'brand' => $brands[1],
                    'DKI Jakarta' => 0,
                    'Jawa Barat' => 0,
                    'Kalimantan' => 0,
                    'Jawa Tengah' => 0,
                    'Bali' => round($persentase_roti_tawar[0],1),
                    ],
        
            ];
        }
        

        return view('cari', compact( 'persentase','brand','area','roti_tawar', 'susu_kaleng','brands', 'persentase_brand'));
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

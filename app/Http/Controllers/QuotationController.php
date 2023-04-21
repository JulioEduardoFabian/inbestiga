<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;
use App\Models\Customer;
use App\Models\Detail;
use App\Models\Order;
use Illuminate\Http\Request;
use PDF;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quotations = Quotation::with(['customer','details','details.product'])->orderBy('id', 'desc')->whereMonth('date', '=', '04')->get();
        return response()->json($quotations);
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
     * @param  \App\Http\Requests\StoreQuotationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* $customer = Customer::create([
            'name' => $request->get('name'),
            'cell' => $request->get('cell'),
            'university' => $request->get('university'),
            'career' => $request->get('career'),
            'grade' => $request->get('grade'),
        ]); */

        $quotation = Quotation::create([
            'customer_id' => $request->get('customer_id'),
            'date' => $request->get('date'),
            'amount' => $request->get('amount'),
            'expiration_date' => $request->get('expirationDay'),
            'discount' => $request->get('discount'),
            'term' => $request->get('term'),
            'note' => $request->get('note')
        ]);

        $products = $request->get('products');
       
        $arrProds = json_decode($products, true);

        return $arrProds;
       
            foreach($arrProds as $prod){
                
                /* $detail = Detail::create([
                    'product_id' => $prod,
                    'quotation_id' => $quotation->id,
                    'type' => 1,
                    'description' => $prod_decode->description,
                    'price' => $prod_decode->total
                ]); */
            }

        $customerToUpdate = Customer::find($request->get('customer_id'))->update([
            'status' => 4
        ]);

        return response()->json([
            'msg' => 'success',
            'id' => $quotation->id
        ]);

        /* return  */
        
        /* foreach($products as $product){
            
        }

        

        

        foreach($sugested_products as $product){
            $detail = Detail::create([
                'product_id' => $product['id'],
                'quotation_id' => $quotation->id,
                'type' => 2
            ]);
        }
 */
       
        
        /* 

        $price = 0;

        foreach($products as $product){
            foreach($product['amount'] as $amount){
                $price = $price+$amount['price'];
            }
        }

        

        
        
         */
    }

    public function generatePDF($id){

        $quotation = Quotation::find($id);
        $customer = Customer::find($quotation->customer_id);
        $details = Detail::with('product')->where('quotation_id',$quotation->id)->get();

        $data = [
            'quotation' => $quotation,
            'customer' => $customer,
            'details' => $details
        ];
        return view('quotation', compact('quotation','customer', 'details'));   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quotation = Quotation::where('id', $id)->with(['customer', 'details', 'details.product','orders'])->get();

        return response()->json($quotation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function edit(Quotation $quotation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQuotationRequest  $request
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuotationRequest $request, Quotation $quotation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quotation $quotation)
    {
        //
    }

    public function newPDF(Request $request){

        $prueba = $request->get('all');


    }

    public function getQuotationByCustomerId($id){
        $customer = Customer::find($id);

        $quotation = Quotation::where('customer_id', $customer->id)->orderBy('id', 'desc')->with(['details', 'details.product','orders'])->get();

        return response()->json($quotation[0]);
    }
    
    public function getQuotationByOrder($id){
        $order = Order::where('id',$id)->with(['quotation', 'quotation.customer','quotation.details', 'quotation.details.product','payments'])->get();

        return response()->json($order[0]);
    }

    public function updateQuotation(Request $request){

        $quotation = Quotation::find($request->get('id_quotation'));

        $quotation->update([
            'date' => $request->get('date'),
            'amount' => $request->get('amount'),
            'expiration_date' => $request->get('expirationDay'),
            'discount' => $request->get('discount'),
            'term' => $request->get('term')
        ]);

        $quotation->details()->delete();

        $products = json_decode($request->get('products'),true);
        

        foreach ($products as $product) {
            $detail = Detail::create([
                'product_id' => $product['product']['id'],
                'quotation_id' => $quotation->id,
                'type' => 1,
                'description' => $product['product']['description'],
                'price' => $product['price']
            ]);
        }

        return response()->json([
            'msg' => 'success',
            'id' => $quotation->id
        ]);
    }

    public function search($search){
        $quotations = Quotation::with('customer')
                ->whereHas('customer', function($query) use ($search) {
                    $query->whereRaw('LOWER(name) like ?', ['%'.strtolower($search).'%']);
                })->get();

        return response()->json($quotations);
    }

    public function searchQuotationsByDate($date){
        $quotations = Quotation::with('customer')->where('date', $date)->get();
        return response()->json($quotations);
    }
}


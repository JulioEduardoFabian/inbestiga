<?php

namespace App\Http\Controllers;

use App\Models\Addendum;
use App\Http\Requests\StoreAddendumRequest;
use App\Http\Requests\UpdateAddendumRequest;
use App\Models\Contract;
use App\Models\Delivery;
use Illuminate\Http\Request;
use PDF;

/**
 * Controlador para la gestión de addendums.
 *
 * Este controlador maneja las operaciones CRUD (Crear, Leer, Actualizar, Eliminar)
 * para los addendums dentro de la aplicación.
 *
 * @category Controller
 * @package  App\Http\Controllers
 */
class AddendumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreAddendumRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $contract = Contract::find($request->contract_id);

        $payments = json_decode($request->get('payments'), true);

        $deliveries = json_decode($request->get('deliveries'), true);

        $addendum = $contract->addendums()->create([
            "date" => date('Y-m-d'),
            "object" => $request->object,
            "clausule" => $request->clausule,
            "status" => 0,
            "amount" => $request->amount,
            "user_id" => $request->user_id,
            "amount_text" => $request->amount_text,
            "cent_text" => $request->cent_text
        ]);

        foreach ($payments as $payment) {
            $addendum->payments()->create([
                "date" => $payment['date'],
                "amount" => $payment['amount'],
                "percentage" =>  $payment['percentage'],
                "status" => $payment['status'] ? 1 : 0,
            ]);
        }

        foreach ($deliveries as $delivery) {

            Delivery::create([
                'advance' => $delivery['advance'],
                'status' => 0,
                'project_id' => $request->project_id,
                'date' => $delivery['date'] ?: null,
                'type' => 1,
                'addendum_id' => $addendum->id
            ]);
        }

        return response()->json($addendum);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Addendum  $addendum
     * @return \Illuminate\Http\Response
     */
    public function show(Addendum $addendum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Addendum  $addendum
     * @return \Illuminate\Http\Response
     */
    public function edit(Addendum $addendum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAddendumRequest  $request
     * @param  \App\Models\Addendum  $addendum
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAddendumRequest $request, Addendum $addendum)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Addendum  $addendum
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $addendum = Addendum::with('deliveries', 'payments')->find($id);

        $addendum->deliveries()->delete();

        $addendum->payments()->delete();

        $addendum->delete();

        return response()->json([
            "msg" => "success",
        ]);
    }

    public function addendumPDF($id)
    {
        $addendum = Addendum::with(['payments', 'deliveries', 'addendumable', 'addendumable.quotation', 'addendumable.quotation.customers'])->find($id);

        $pdf = PDF::loadView('addendum', compact('addendum'));

        return $pdf->stream('addendum.pdf');
    }

    public function updateStatus(Request $request, $id)
    {
        $addendum = Addendum::find($id);
        $addendum->status = $request->status;
        $addendum->save();
        return response()->json($addendum);
    }
}

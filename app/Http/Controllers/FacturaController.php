<?php

namespace App\Http\Controllers;

use App\Http\Requests\Billing\StoreHonorarioFacturaRequest;
use App\honorario;
use App\Services\Billing\FacturaFileService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FacturaController extends Controller
{
    private FacturaFileService $facturaFiles;

    public function __construct(FacturaFileService $facturaFiles)
    {
        $this->middleware("auth");
        $this->facturaFiles = $facturaFiles;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("factura.index",[
            "facturas" => $this->adminFacturas()
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function MisFacturas(){
        return view("factura.misFacturas",[
            "facturas" => Auth::user()->honorarios()->with('user')->whereNotNull('factura')->orderByDesc('id')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $honorario = honorario::findOrFail($id);
        abort_unless((int) $honorario->user_id === (int) Auth::id(), 403);

        return view("factura.create",["honorario" => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHonorarioFacturaRequest $request)
    {
        $data = $request->validated();
        $honorario = honorario::findOrFail($data["honorario"]);
        abort_unless((int) $honorario->user_id === (int) Auth::id(), 403);

        $this->facturaFiles->uploadForHonorario($honorario, $request->file("file"));

        return redirect("/misHonorarios");

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $path = $this->facturaFiles->viewablePathFor((int) $id, Auth::user());

        return response()->file(Storage::path($path));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $honorario = honorario::findOrFail($id);
        abort_unless(
            (int) $honorario->user_id === (int) Auth::id() || Auth::user()->canAccessAnyMenu(['/honorarios']),
            403
        );

        $this->facturaFiles->deleteForHonorario($honorario);

        return redirect("/misFacturas");
    }

    private function adminFacturas()
    {
        return honorario::with('user')->whereNotNull('factura')->orderByDesc('id')->get();
    }
}

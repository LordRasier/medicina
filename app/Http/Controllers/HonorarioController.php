<?php

namespace App\Http\Controllers;


use App\Http\Requests\Billing\StoreHonorariosRequest;
use App\honorario;
use App\Services\Billing\HonorarioImportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HonorarioController extends Controller
{
    private HonorarioImportService $honorarioImporter;

    public function __construct(HonorarioImportService $honorarioImporter)
    {
        $this->middleware("auth");
        $this->honorarioImporter = $honorarioImporter;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("honorario.index",["honorarios" => $this->adminHonorarios()]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function MisHonorarios(){
        return view("honorario.listado",[
            "honorarios" => Auth::user()->honorarios()->orderBy("id","desc")->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("honorario.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHonorariosRequest $request)
    {
        $summary = $this->honorarioImporter->import($request->file("file", []));

        return view("honorario.index",[
            "honorarios" => $this->adminHonorarios(),
            "result" => true,
            "importSummary" => $summary,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $honorario = honorario::findOrFail($id);
        $user = Auth::user();

        abort_unless(
            (int) $honorario->user_id === (int) Auth::id() || $user->canAccessAnyMenu(['/honorarios']),
            403
        );
        abort_if($honorario->file == null || ! Storage::exists($honorario->file), 404);

        return response()->file(Storage::path($honorario->file));
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
        $hono = honorario::findOrFail($id);
        $hono->delete();

        return view("honorario.index",["honorarios" => $this->adminHonorarios(),"elim" => true]);
    }

    private function adminHonorarios()
    {
        return honorario::with('user')->orderByDesc('id')->get();
    }
}

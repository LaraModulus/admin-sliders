<?php

namespace LaraMod\Admin\Sliders\Controllers;

use App\Http\Controllers\Controller;
use \LaraMod\Admin\Sliders\Models\Sliders;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class SlidersController extends Controller
{

    private $data = [];

    public function __construct()
    {
        config()->set('admincore.menu.sliders.active', true);
    }

    public function index()
    {
        $this->data['items'] = Sliders::paginate(20);

        return view('adminsliders::sliders.list', $this->data);
    }

    public function getForm(Request $request)
    {
        $this->data['item'] = ($request->has('id') ? Sliders::find($request->get('id')) : new Sliders());

        return view('adminsliders::sliders.form', $this->data);
    }

    public function postForm(Request $request)
    {

        $item = Sliders::firstOrNew(['id' => $request->get('id')]);
        try {
            $item->autoFill($request);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['errors' => $e->getMessage(). ' at '. $e->getFile()]);
        }

        return redirect()->route('admin.sliders')->with('message', [
            'type' => 'success',
            'text' => 'Item saved.',
        ]);
    }

    public function delete(Request $request)
    {
        if (!$request->has('id')) {
            return redirect()->route('admin.sliders')->with('message', [
                'type' => 'danger',
                'text' => 'No ID provided!',
            ]);
        }
        try {
            Sliders::find($request->get('id'))->delete();
        } catch (\Exception $e) {
            return redirect()->route('admin.sliders')->with('message', [
                'type' => 'danger',
                'text' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.sliders')->with('message', [
            'type' => 'success',
            'text' => 'Item moved to trash.',
        ]);
    }

    public function dataTable()
    {
        $items = Sliders::select(['id', 'viewable', 'created_at', 'from_date', 'to_date', 'title_'.config('app.fallback_locale', 'en'), 'image_'.config('app.fallback_locale', 'en')]);

        return DataTables::of($items)
            ->addColumn('action', function ($item) {
                return '<a href="' . route('admin.sliders.form',
                        ['id' => $item->id]) . '" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>'
                    . '<a href="' . route('admin.sliders.delete',
                        ['id' => $item->id]) . '" class="btn btn-danger btn-xs require-confirm"><i class="fa fa-trash"></i></a>';
            })
            ->editColumn('created_at', function ($item) {
                return $item->created_at->format('d.m.Y H:i');
            })
            ->editColumn('from_date', function ($item) {
                return $item->from_date->format('d.m.Y H:i');
            })
            ->editColumn('to_date', function ($item) {
                if(is_null($item->to_date)) return null;
                return $item->to_date->format('d.m.Y H:i');
            })
            ->addColumn('status', function ($item) {
                return !$item->viewable && ($item->from_date > \Carbon\Carbon::now() || $item->to_date < \Carbon\Carbon::now()) ? '<i class="fa fa-eye-slash"></i>' : '<i class="fa fa-eye"></i>';
            })
            ->orderColumn('created_at $1', 'id $1')
            ->make('true');
    }


}
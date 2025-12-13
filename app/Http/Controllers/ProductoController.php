<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ProductosExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::query();

        // Aplicar filtros
        if ($request->filled('codigo')) {
            $query->where('codigo', 'like', '%' . $request->codigo . '%');
        }

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('marca')) {
            $query->where('marca', 'like', '%' . $request->marca . '%');
        }

        if ($request->filled('presentacion_tipo')) {
            $query->where('presentacion_tipo', $request->presentacion_tipo);
        }

        $productos = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return Inertia::render('Productos/Index', [
            'productos' => $productos,
            'filters' => $request->only(['codigo', 'nombre', 'marca', 'presentacion_tipo'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('productos.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|unique:productos,codigo',
            'nombre' => 'required|string|max:255',
            'presentacion_tipo' => 'required|in:unidad,peso',
            'presentacion_valor' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'valor_costo' => 'required|numeric|min:0',
            'valor_venta' => 'required|numeric|min:0',
            'marca' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'productos/' . $filename;
            
            // Upload to DigitalOcean Spaces
            Storage::disk('spaces')->put($path, file_get_contents($file), 'public');
            
            // Get the public URL
            $validated['imagen'] = Storage::disk('spaces')->url($path);
        }

        Producto::create($validated);

        return back()->with('success', 'Producto creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return Inertia::render('Productos/Show', [
            'producto' => $producto
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        return redirect()->route('productos.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|unique:productos,codigo,' . $producto->id,
            'nombre' => 'required|string|max:255',
            'presentacion_tipo' => 'required|in:unidad,peso',
            'presentacion_valor' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'valor_costo' => 'required|numeric|min:0',
            'valor_venta' => 'required|numeric|min:0',
            'marca' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        if ($request->hasFile('imagen')) {
            // Delete old image from Spaces if exists
            if ($producto->imagen) {
                $oldPath = parse_url($producto->imagen, PHP_URL_PATH);
                $oldPath = ltrim($oldPath, '/');
                // Extract path after bucket name
                $parts = explode('/', $oldPath, 2);
                if (count($parts) > 1) {
                    Storage::disk('spaces')->delete($parts[1]);
                }
            }
            
            $file = $request->file('imagen');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'productos/' . $filename;
            
            // Upload to DigitalOcean Spaces
            Storage::disk('spaces')->put($path, file_get_contents($file), 'public');
            
            // Get the public URL
            $validated['imagen'] = Storage::disk('spaces')->url($path);
        }

        $producto->update($validated);

        return back()->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        // Delete image from Spaces if exists
        if ($producto->imagen) {
            $oldPath = parse_url($producto->imagen, PHP_URL_PATH);
            $oldPath = ltrim($oldPath, '/');
            // Extract path after bucket name
            $parts = explode('/', $oldPath, 2);
            if (count($parts) > 1) {
                Storage::disk('spaces')->delete($parts[1]);
            }
        }

        $producto->delete();

        return back()->with('success', 'Producto eliminado exitosamente');
    }

    /**
     * Export to PDF
     */
    public function exportPdf()
    {
        $productos = Producto::orderBy('nombre')->get();
        
        $pdf = Pdf::loadView('productos.pdf', compact('productos'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->download('productos_' . now()->format('Y-m-d_His') . '.pdf');
    }

    /**
     * Export to Excel
     */
    public function exportExcel()
    {
        return Excel::download(
            new ProductosExport, 
            'productos_' . now()->format('Y-m-d_His') . '.xlsx'
        );
    }
}

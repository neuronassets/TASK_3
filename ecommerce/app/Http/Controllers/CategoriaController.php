<?php

namespace App\Http\Controllers;

use App\Models\Nodo;

class CategoriaController extends Controller {
    
    /**
     * Displays a tree view of categories starting from the root nodes.
     *
     * This method gets the top-level nodes where 'nodi_id_padre' is 0.
     * It gets the nodes from the database using the `Nodo` model and then passes them to 
     * a view for display. 
     * 
     * @return \Illuminate\View\View
     */
    public function stampaAlbero() {
    
        try {
            
            // get root nodes
            $nodiPrimoLivello = Nodo::where('nodi_id_padre', 0)->get();
            
            // pass root nodes to the view
            return view('categorie.albero', compact('nodiPrimoLivello'));
            
        } catch (\Exception $e) {
            
            return response()->view('errors.general', 
                ['message' => 'Unable to load categories'], 500);
        }
    }
    
}


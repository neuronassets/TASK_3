<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nodo extends Model {
    
    // db table name
    protected $table = 'Nodi'; 
    // primary key
    protected $primaryKey = 'nodi_id';
    protected $fillable = ['nodi_id_padre'];
    
    
    /**
     * Retrieve the child nodes of this model in file nodo.blade.php
     *
     * This method establishes a recursive one-to-many relationship within the `Nodo` model itself.
     * It uses the 'nodi_id_padre' to identify the parent node.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function figli() {
        
        // load children recursively
        return $this->hasMany(Nodo::class, 'nodi_id_padre', 'nodi_id')
        ->with('figli'); 
    }
    
}

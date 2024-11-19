<?php

/**
 * Program to populate a table with all parent nodes of a specific article.
 *
 * This script processes a specific `articolo_id`, identifies its associated leaf nodes,
 * traverses their hierarchy to find all parent nodes, and populates a new table
 * `articoli_rami` with these relationships.
 *
 * Tables:
 * - `articoli`: Contains articles.
 * - `articoli_nodi`: Links articles to nodes.
 * - `nodi`: Defines the hierarchical structure of nodes.
 * - `articoli_rami`: Populated with article and parent node relationships.
 */

// db connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    
    die("Connessione fallita: " . $conn->connect_error);
}

/**
 * Finds all parent nodes of a given node in the hierarchy.
 *
 * @param int $nodoId The ID of the current node.
 * @param mysqli $conn The MySQL database connection object.
 * @return int[] An array of parent node IDs.
 */
function getParentNodes($nodoId, $conn) {
    
    $nodiPadri = [];
    
    while ($nodoId) {
        
        // gets nodo_id parent
        $query = "SELECT nodi_id_padre FROM nodi WHERE nodi_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $nodoId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // gets the next row from the result set as a key-value pair
        if ($row = $result->fetch_assoc()) {
            
            $nodoIdPadre = $row['nodi_id_padre'];
            
            if ($nodoIdPadre) {
                // add nodo_id_padre to nodi_padri array
                $nodiPadri[] = $nodoIdPadre;
            }
            
            $nodoId = $nodoIdPadre;
        } else {
            break;
        }
        $stmt->close();
    }
    
    return $nodiPadri;
}

/**
 * Processes a specific articolo_id to populate articolo_rami table.
 *
 * @param int $articoloId The ID of the article to process.
 * @param mysqli $conn The MySQL database connection object.
 * @return void
 */
function processArticle($articoloId, $conn) {
    
    // query to find leaf node associated with the article
    $query = "
        SELECT an.nodo_id
        FROM articoli_nodi an
        WHERE an.articolo_id = ?
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $articoloId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // gets the next row from the result set as a key-value pair
    while ($row = $result->fetch_assoc()) {
        
        $nodoIdFoglia = $row['nodo_id'];
        
        // find all parent nodes for the leaf node
        $nodiPadri = getParentNodes($nodoIdFoglia, $conn);
        
        // insert parent node relationships into articoli_rami
        foreach ($nodiPadri as $nodoPadre) {
            
            $insertQuery = "INSERT INTO articoli_rami (articolo_id, nodo_id_padre) VALUES (?, ?)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("ii", $articoloId, $nodoPadre);
            $insertStmt->execute();
            $insertStmt->close();
        }
    }
    
    $stmt->close();
}

// example usage
$articoloId = 1; 

processArticle($articoloId, $conn);

echo "Dati popolati con successo nella tabella articoli_rami.";

$conn->close();

?>


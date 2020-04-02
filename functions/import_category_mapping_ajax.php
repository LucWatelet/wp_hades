<?php
namespace Atlb\Hades;

function import_category_mapping_ajax()
{
    var_dump($_POST);
    var_dump($_FILES['import_file']);
    if (move_uploaded_file($_FILES['import_file']['tmp_name'], $uploadfile)) {
        echo "Le fichier est valide, et a été téléchargé avec succès. Voici plus d'informations :\n";
    } else {
        echo "Attaque potentielle par téléchargement de fichiers. Voici plus d'informations :\n";
    }
    echo 'Voici quelques informations de débogage :';
    var_dump($_FILES);
    import_category_mapping($tree_json, $_POST['type_import']);
}

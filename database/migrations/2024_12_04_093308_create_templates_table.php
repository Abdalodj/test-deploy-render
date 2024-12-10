<?php 


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du template
            $table->text('html_content')->nullable(); // Contenu HTML du template
            $table->json('json_structure')->nullable(); // Structure JSON du template
            $table->string('thumbnail_url')->nullable(); // URL de l'image représentative
            $table->string('deployment_link')->nullable(); // Lien de déploiement pour l'aperçu
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('templates');
    }
}

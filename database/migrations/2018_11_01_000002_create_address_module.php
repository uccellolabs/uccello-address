<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Uccello\Core\Models\Module;
use Uccello\Core\Models\Domain;
use Uccello\Core\Models\Tab;
use Uccello\Core\Models\Block;
use Uccello\Core\Models\Field;
use Uccello\Core\Models\Filter;

class CreateAddressModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->createTable();

        $module = $this->createModule();
        $this->activateModuleOnDomains($module);
        $this->createTabsBlocksFields($module);
        $this->createFilters($module);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop table
        Schema::dropIfExists('addresses');

        // Delete module
        Module::where('name', 'address')->forceDelete();
    }

    protected function createTable()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city');
            $table->string('country_id')->nullable();
            $table->timestamps();
        });
    }

    protected function createModule()
    {
        $module = new  Module();
        $module->name = 'address';
        $module->icon = 'location_on';
        $module->model_class = 'Uccello\Address\Models\Address';
        $module->data = ["package" => "uccello/address", "menu" => false];
        $module->save();

        return $module;
    }

    protected function activateModuleOnDomains($module)
    {
        $domains = Domain::all();

        foreach ($domains as $domain) {
            $domain->modules()->attach($module);
        }
    }

    protected function createTabsBlocksFields($module)
    {
        // Main tab
        $tab = new Tab();
        $tab->label = 'tab.main';
        $tab->icon = null;
        $tab->sequence = 0;
        $tab->module_id = $module->id;
        $tab->save();

        // Address block
        $block = new Block();
        $block->label = 'block.address';
        $block->icon = 'location_on';
        $block->sequence = 0;
        $block->tab_id = $tab->id;
        $block->module_id = $module->id;
        $block->save();

        // Address
        $field = new Field();
        $field->name = 'address_1';
        $field->uitype_id = uitype('text')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = ['rules' => 'required'];
        $field->sequence = 0;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Address complement
        $field = new Field();
        $field->name = 'address_2';
        $field->uitype_id = uitype('text')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = null;
        $field->sequence = 1;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Postal code
        $field = new Field();
        $field->name = 'postal_code';
        $field->uitype_id = uitype('text')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = null;
        $field->sequence = 2;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // City
        $field = new Field();
        $field->name = 'city';
        $field->uitype_id = uitype('text')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = ['rules' => 'required'];
        $field->sequence = 3;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();

        // Country
        $field = new Field();
        $field->name = 'country';
        $field->uitype_id = uitype('entity')->id;
        $field->displaytype_id = displaytype('everywhere')->id;
        $field->data = ['rules' => 'required', 'module' => 'country', 'field' => 'name'];
        $field->sequence = 4;
        $field->block_id = $block->id;
        $field->module_id = $module->id;
        $field->save();
    }

    protected function createFilters($module)
    {
        $filter = new Filter();
        $filter->module_id = $module->id;
        $filter->domain_id = null;
        $filter->user_id = null;
        $filter->name = 'filter.all';
        $filter->type = 'list';
        $filter->columns = ['address_1', 'address_2', 'postal_code', 'city', 'country'];
        $filter->conditions = null;
        $filter->order = null;
        $filter->is_default = true;
        $filter->is_public = false;
        $filter->data = [ 'readonly' => true ];
        $filter->save();
    }
}

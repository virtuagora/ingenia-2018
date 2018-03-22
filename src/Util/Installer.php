<?php namespace App\Util;

class Installer
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function up()
    {
        $this->db->schema()->create('options', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type'); //integer, string, text, hidden
            $table->string('group');
            $table->boolean('autoload');
            $table->timestamps();
        });
        $this->db->schema()->create('regions', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('region');
            $table->string('name');
        });
        $this->db->schema()->create('departments', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->integer('region_id')->unsigned();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });
        $this->db->schema()->create('localities', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->boolean('custom')->default(false);
            $table->integer('department_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });

        $this->db->schema()->create('subjects', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('display_name');
            $table->integer('img_type')->unsigned();
            $table->string('img_hash');
            $table->integer('points')->default(0);
            $table->string('type');
        });
        $this->db->schema()->create('roles', function($table) {
            $table->engine = 'InnoDB';
            $table->string('id')->primary();
            $table->string('name')->unique();
            $table->boolean('show_badge');
            $table->string('icon')->nullable();
            $table->text('meta')->nullable();
            //$table->json('meta')->nullable();
            $table->timestamps();
        });
        $this->db->schema()->create('subject_role', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamp('expiration')->nullable();
            $table->integer('subject_id')->unsigned();
            $table->string('role_id');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
        $this->db->schema()->create('users', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('password')->nullable();
            $table->string('names');
            $table->string('surnames');

            $table->timestamp('birthday')->nullable();
            $table->string('gender')->nullable();
            $table->string('address')->nullable();
            $table->string('telephone')->nullable();
            $table->string('dni')->nullable();
            $table->boolean('verified_dni')->default(false);
            $table->string('pending_email')->nullable();

            $table->integer('locality_id')->unsigned()->nullable();
            $table->foreign('locality_id')->references('id')->on('localities');
            $table->string('locality_other')->nullable();

            $table->string('token')->nullable();
            $table->timestamp('token_expiration')->nullable();
            $table->timestamp('ban_expiration')->nullable();
            $table->string('trace')->nullable();
            $table->integer('subject_id')->unsigned()->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->index('email');
            $table->index('facebook');
        });
        $this->db->schema()->create('pending_users', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('provider');
            $table->string('identifier');
            $table->string('token');
            $table->text('fields')->nullable();
            $table->timestamps();
            $table->unique(['provider', 'identifier']);
            $table->index('token');
        });
        $this->db->schema()->create('groups', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->integer('year');
            $table->text('previous_editions'); //json
            $table->string('parent_organization')->nullable();
            $table->string('web')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('referer')->nullable(); //como se enteraron

            $table->integer('locality_id')->unsigned();
            $table->foreign('locality_id')->references('id')->on('localities');
            $table->string('locality_other')->nullable();

            $table->integer('quota')->unsigned()->nullable();
            $table->string('trace')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('groups')->onDelete('set null');
            $table->integer('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        $this->db->schema()->create('user_group', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('relation'); // miembro, co-responsable, responsable
            $table->string('title')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
        $this->db->schema()->create('projects', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('abstract');
            $table->text('foundation');
            $table->string('topic');
            $table->string('in_progress');
            $table->string('neighbourhood');
            $table->text('goal');
            $table->text('budget');
            $table->text('schedule');
            $table->string('partners')->nullable();

            $table->boolean('public')->default(false);
            $table->boolean('selected')->default(false);
            $table->boolean('has_image')->default(false);
            $table->integer('likes')->default(0);
            $table->string('trace')->nullable();
            $table->text('notes')->nullable(); //observaciones internas

            $table->integer('locality_id')->unsigned();
            $table->foreign('locality_id')->references('id')->on('localities');
            $table->string('locality_other')->nullable();

            $table->integer('group_id')->unsigned()->nullable();
            $table->foreign('group_id')->references('id')->on('groups');

            $table->timestamps();
            $table->softDeletes();
        });
        $this->db->schema()->create('comments', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('content');
            $table->integer('votes')->default(0);
            $table->text('meta')->nullable();
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->integer('author_id')->unsigned();
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        $this->db->schema()->create('comment_votes', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('value');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('comment_id')->unsigned();
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->timestamps();
        });
        
        $this->db->schema()->create('actions', function($table) {
            $table->engine = 'InnoDB';
            $table->string('id')->primary();
            $table->string('group');
            $table->string('allowed_roles');
            $table->string('allowed_relations');
            $table->string('allowed_proxies');
            $table->timestamps();
        });
        $this->db->schema()->create('pages', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('link')->nullable();
            $table->text('meta')->nullable();
            //$table->json('meta')->nullable();
            $table->string('slug');
            $table->integer('order')->default(0);
            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('pages')->onDelete('set null');
        });
        $this->db->schema()->create('logs', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('subject_id')->unsigned();
            $table->integer('proxy_id')->unsigned()->nullable();
            $table->string('action_id');
            $table->string('object_type');
            $table->integer('object_id')->unsigned();
            $table->text('meta')->nullable();
            //$table->json('meta')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('proxy_id')->references('id')->on('subjects')->onDelete('set null');
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->index(['object_type', 'object_id']);
            $table->timestamps();
        });
        $this->db->schema()->create('notifications', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('seen')->default(false);
            $table->integer('log_id')->unsigned();
            $table->foreign('log_id')->references('id')->on('logs')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        $this->db->schema()->dropIfExists('notifications');
        $this->db->schema()->dropIfExists('logs');
        $this->db->schema()->dropIfExists('pages');
        $this->db->schema()->dropIfExists('actions');
        $this->db->schema()->dropIfExists('comment_votes');
        $this->db->schema()->dropIfExists('comments');
        $this->db->schema()->dropIfExists('projects');
        $this->db->schema()->dropIfExists('user_group');
        $this->db->schema()->dropIfExists('groups');
        $this->db->schema()->dropIfExists('pending_users');
        $this->db->schema()->dropIfExists('users');
        $this->db->schema()->dropIfExists('subject_role');
        $this->db->schema()->dropIfExists('roles');
        $this->db->schema()->dropIfExists('subjects');
        $this->db->schema()->dropIfExists('localities');
        $this->db->schema()->dropIfExists('departments');
        $this->db->schema()->dropIfExists('departaments');
        $this->db->schema()->dropIfExists('regions');
        $this->db->schema()->dropIfExists('options');
    }

    public function populate()
    {
        $this->db->table('roles')->insert([
            [
                'id' => 'user',
                'name' => 'Usuario',
                'show_badge' => false,
            ], [
                'id' => 'verified',
                'name' => 'Verificado',
                'show_badge' => false,
            ], [
                'id' => 'admin',
                'name' => 'Admnistrador',
                'show_badge' => true,
            ], [
                'id' => 'coordin',
                'name' => 'Coordinador',
                'show_badge' => true,
            ], [
                'id' => 'group',
                'name' => 'Grupo',
                'show_badge' => false,
            ],
        ]);
    }
}

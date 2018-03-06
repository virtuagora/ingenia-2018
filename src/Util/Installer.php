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
            $table->string('email')->unique();
            $table->string('password');
            $table->string('names');
            $table->string('surnames');
            $table->string('token')->nullable();
            $table->timestamp('token_expiration')->nullable();
            $table->timestamp('ban_expiration')->nullable();
            $table->string('trace')->nullable();
            $table->integer('subject_id')->unsigned()->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        $this->db->schema()->create('user_meta', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('key');
            $table->text('value');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('key');
        });
        $this->db->schema()->create('pending_users', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('provider');
            $table->string('identifier');
            $table->string('activation_key');
            $table->text('fields')->nullable();
            $table->timestamps();
            $table->unique(['provider', 'identifier']);
            $table->index('activation_key');
        });
        $this->db->schema()->create('group_types', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            //$table->string('role_policy'); // enum(single, group, empty, custom)
            $table->text('description');
            $table->text('allowed_relations')->nullable(); // list of allowed relations for every group
            $table->text('meta')->nullable();
            //$table->json('meta')->nullable();
            $table->string('role_id')->nullable(); // role for the groups of this type
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->timestamps();
        });
        $this->db->schema()->create('groups', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('acronym')->nullable();
            $table->text('description');
            $table->integer('quota')->unsigned()->nullable();
            $table->string('trace')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('groups')->onDelete('set null');
            $table->integer('group_type_id')->unsigned();
            $table->foreign('group_type_id')->references('id')->on('group_types')->onDelete('restrict');
            $table->integer('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        $this->db->schema()->create('group_meta', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('group_id')->unsigned();
            $table->string('key');
            $table->text('value');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->index('key');
        });
        $this->db->schema()->create('user_group', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('relation');
            $table->string('title')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
        $this->db->schema()->create('nodes', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->string('type');
            $table->text('content')->nullable();
            $table->integer('points')->default(0);
            $table->dateTime('close_date')->nullable();
            $table->boolean('unlisted');
            $table->string('trace')->nullable();
            $table->integer('author_id')->unsigned();
            $table->foreign('author_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        $this->db->schema()->create('node_meta', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('node_id')->unsigned();
            $table->string('key');
            $table->text('value');
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
            $table->index('key');
        });
        $this->db->schema()->create('node_node', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('relation')->nullable();
            $table->integer('parent_id')->unsigned();
            $table->foreign('parent_id')->references('id')->on('nodes')->onDelete('cascade');
            $table->integer('child_id')->unsigned();
            $table->foreign('child_id')->references('id')->on('nodes')->onDelete('cascade');
        });
        $this->db->schema()->create('node_subject', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('relation');
            $table->integer('node_id')->unsigned();
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
            $table->integer('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
        $this->db->schema()->create('comments', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('content');
            $table->integer('votes')->default(0);
            $table->text('meta')->nullable();
            //$table->json('meta')->nullable();
            $table->integer('node_id')->unsigned();
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
            $table->integer('author_id')->unsigned();
            $table->foreign('author_id')->references('id')->on('subjects')->onDelete('cascade');
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
        $this->db->schema()->create('terms', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('taxonomy');
            $table->integer('count')->unsigned()->default(0);
            $table->timestamps();
            $table->unique(['slug', 'taxonomy']);
        });
        $this->db->schema()->create('term_object', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('term_id')->unsigned();
            $table->string('object_type');
            $table->integer('object_id')->unsigned();
            $table->text('meta')->nullable();
            //$table->json('meta')->nullable();
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
            $table->index(['object_type', 'object_id']);
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
            $table->integer('node_id')->unsigned()->nullable();
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
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

        // --- Plugin suscriptions ---

        $this->db->schema()->create('suscriptions', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('priority')->default(0);
            $table->integer('user_id')->unsigned();
            $table->string('publisher_type');
            $table->integer('publisher_id')->unsigned();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['publisher_type', 'publisher_id']);
        });

        // --- Plugin content ballots ---

        $this->db->schema()->create('ballots', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('options');
            $table->integer('node_id')->unsigned();
            $table->foreign('node_id')->references('id')->on('nodes')->onDelete('cascade');
        });
        $this->db->schema()->create('ballot_user', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('vote');
            $table->boolean('is_public');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('ballot_id')->unsigned();
            $table->foreign('ballot_id')->references('id')->on('ballots')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->db->schema()->dropIfExists('ballot_user');
        $this->db->schema()->dropIfExists('ballots');
        $this->db->schema()->dropIfExists('suscriptions');
        $this->db->schema()->dropIfExists('notifications');
        $this->db->schema()->dropIfExists('logs');
        $this->db->schema()->dropIfExists('pages');
        $this->db->schema()->dropIfExists('actions');
        $this->db->schema()->dropIfExists('term_object');
        $this->db->schema()->dropIfExists('terms');
        $this->db->schema()->dropIfExists('comment_votes');
        $this->db->schema()->dropIfExists('comments');
        $this->db->schema()->dropIfExists('node_subject');
        $this->db->schema()->dropIfExists('node_node');
        $this->db->schema()->dropIfExists('node_meta');
        $this->db->schema()->dropIfExists('nodes');
        $this->db->schema()->dropIfExists('user_group');
        $this->db->schema()->dropIfExists('group_meta');
        $this->db->schema()->dropIfExists('groups');
        $this->db->schema()->dropIfExists('group_types');
        $this->db->schema()->dropIfExists('pending_users');
        $this->db->schema()->dropIfExists('user_meta');
        $this->db->schema()->dropIfExists('users');
        $this->db->schema()->dropIfExists('subject_role');
        $this->db->schema()->dropIfExists('roles');
        $this->db->schema()->dropIfExists('subjects');
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
                'show_badge' => true,
            ], [
                'id' => 'admin',
                'name' => 'Admnistrador',
                'show_badge' => true,
            ], [
                'id' => 'group-staff',
                'name' => 'Grupo de Staff',
                'show_badge' => false,
            ],
        ]);

        $this->db->table('group_types')->insert([
            [
                'name' => 'Staff',
                'description' => 'Equipo de trabajo de Virtuagora',
                'role_id' => 'group-staff'
            ],
        ]);
    }
}

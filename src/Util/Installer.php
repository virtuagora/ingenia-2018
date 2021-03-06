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
        $this->db->schema()->create('categories', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('description');
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
            $table->boolean('verified_dni')->nullable();
            $table->string('pending_email')->nullable();
            $table->text('bio')->nullable();
            $table->string('referer')->nullable(); //como se enteraron
            $table->string('referer_other')->nullable();
            $table->string('neighbourhood')->nullable();

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
            $table->text('parent_organization')->nullable();
            $table->string('web')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();

            $table->boolean('uploaded_agreement')->default(false);
            $table->boolean('uploaded_letter')->default(false);
            $table->boolean('full_team')->default(false);
            $table->boolean('second_in_charge')->default(false);
            $table->boolean('verified_team')->default(false);

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
        $this->db->schema()->create('invitations', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('state'); // miembro, co-responsable, responsable
            $table->string('email')->nullable();
            $table->string('comment')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->timestamps();
        });
        $this->db->schema()->create('projects', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->text('abstract');
            $table->text('foundation');
            $table->text('previous_work')->nullable();
            $table->text('neighbourhoods');
            $table->text('goals');
            $table->text('budget');
            $table->text('schedule');
            $table->text('organization')->nullable();

            $table->boolean('public')->default(false);
            $table->boolean('selected')->default(false);
            $table->boolean('has_image')->default(false);
            $table->decimal('total_budget')->default(0);
            $table->decimal('granted_budget')->default(0);
            $table->integer('likes')->default(0);
            $table->string('trace')->nullable();
            $table->text('notes')->nullable(); //observaciones internas

            $table->integer('coordin_id')->unsigned()->nullable();
            $table->foreign('coordin_id')->references('id')->on('users')->onDelete('set null');

            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');

            $table->integer('locality_id')->unsigned();
            $table->foreign('locality_id')->references('id')->on('localities');
            $table->string('locality_other')->nullable();

            $table->integer('group_id')->unsigned()->nullable();
            $table->foreign('group_id')->references('id')->on('groups');

            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
        });
        $this->db->schema()->create('project_user', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->timestamps();
        });
        $this->db->schema()->create('comments', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->text('content');
            $table->integer('votes')->default(0);
            $table->text('meta')->nullable();
            $table->integer('project_id')->unsigned()->nullable();
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

        $this->db->schema()->create('receipts', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('file')->nullable();
            $table->decimal('amount')->default(0);
            $table->date('date')->nullable();
            $table->text('detail')->nullable();
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->timestamps();
        });
        $this->db->schema()->create('stories', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('picture')->nullable();
            $table->text('body')->nullable();
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->timestamps();
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
        $this->db->schema()->dropIfExists('project_user');
        $this->db->schema()->dropIfExists('projects');
        $this->db->schema()->dropIfExists('invitations');
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
        $this->db->schema()->dropIfExists('categories');
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

        $this->db->table('options')->insert([
            [
                'key' => 'dni-blacklist',
                'value' => '[]',
                'type' => 'array',
                'group' => 'varios',
                'autoload' => false,
            ], [
                'key' => 'deadline',
                'value' => '2018-6-8 23:59:59',
                'type' => 'date',
                'group' => 'varios',
                'autoload' => true,
            ], [
                'key' => 'stat-votes',
                'value' => '0',
                'type' => 'integer',
                'group' => 'estadisticas',
                'autoload' => true,
            ], [
                'key' => 'stat-comments',
                'value' => '0',
                'type' => 'integer',
                'group' => 'estadisticas',
                'autoload' => true,
            ],
        ]);

        $this->db->table('categories')->insert([
            [
                'name' => 'Integracion Social',
                'description' => 'Incluimos aqu?? aquellos proyectos cuyos objetivos y actividades apuntaban a mejorar la convivencia a nivel social, implementando acciones destinadas a j??venes, y a poblaci??n en general, que tengan algunos de sus derechos vulnerados. Entre ellos j??venes en situaci??n de vulnerabilidad social, j??venes con discapacidad, entre otros.',
            ], [
                'name' => 'Medio Ambiente',
                'description' => 'Esta categor??a engloba a los proyectos que se propusieron mejorar las condiciones del medio ambiente. Los mismos enfocan una amplia variedad de tem??ticas como ser las energ??as renovables, la producci??n verde, el reciclado de residuos, la soberan??a alimentaria y la movilidad sustentable.',
            ], [
                'name' => 'Deporte y recreaci??n',
                'description' => 'Este eje integra los proyectos abocados a promover el bienestar joven mediante la organizaci??n de actividades deportivas y recreativas. En muchos casos, se trataban de proyectos que pretend??an la recuperaci??n de espacios p??blicos-en su mayor??a plazas p??blicas- para tales fines.',
            ], [
                'name' => 'Educaci??n',
                'description' => 'Se incluyen en esta categor??a a los proyectos juveniles cuyos objetivos y actividades tienen como fin brindar mayor educaci??n a la sociedad en general. La educaci??n es entendida en sentido amplio por los j??venes, abarcando desde la ense??anza de los propios derechos hasta la educaci??n sexual, adoptando desde formatos tradicionales de jornadas de formaci??n b??sicas hasta actividades l??dicas.                ',
            ], [
                'name' => 'Arte y Cultura',
                'description' => 'Caben aqu?? las iniciativas orientadas a al promoci??n de la cultura local, regional y provincial, desde diferentes expresiones art??sticas: danzas, teatro, m??sica, pintura, productos artesanales, entre otros',
            ], [
                'name' => 'Empleo y Capacitaci??n',
                'description' => 'Se encuentran en esta categor??a los proyectos juveniles cuyos intereses se relacionaban con el mundo del trabajo, la posibilidad de acceder al mismo y la mejora de las condiciones laborales. Aqu??, se encuentran tambi??n algunas iniciativas que, no atendiendo a las bases del Programa Ingenia, se formularon con la intenci??n de lograr un microemprendimiento de car??cter privado.',
            ], [
                'name' => 'Comunicaci??n',
                'description' => 'Se abarca con este eje a los proyectos destinados a amplificar la voz joven. Caben aqu?? las acciones para crear medios de comunicaci??n gr??ficos y audiovisuales, como as?? tambi??n aquellas actividades que promocionan la participaci??n juvenil en los medios existentes.',
            ], [
                'name' => 'Salud y Discapacidad',
                'description' => 'Iniciativas que promueven una vida saludable en las juventudes. Reflexionar sobre las problem??ticas vinculadas a la salud y el acceso a la misma sin olvidar que ??sta no s??lo depende de la biolog??a o de la conducta del individuo, sino tambi??n de factores sociales, pol??ticos y culturales sobre los que son necesarios actuar para producir cambios.',
            ]
        ]);
    }
}

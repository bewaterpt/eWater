create table 'users' ('id' bigint unsigned not null auto_increment primary key, 'name' varchar(255) not null, 'email' varchar(255) null, 'username' varchar(255) not null, 'enabled' tinyint(1) not null default '1', 'password' varchar(255) not null, 'remember_token' varchar(100) null, 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
alter table 'users' add unique 'users_username_unique'('username');
create table 'telescope_entries' ('sequence' bigint unsigned not null auto_increment primary key, 'uuid' char(36) not null, 'batch_id' char(36) not null, 'family_hash' varchar(255) null, 'should_display_on_index' tinyint(1) not null default '1', 'type' varchar(20) not null, 'content' longtext not null, 'created_at' datetime null) default character set utf8 collate 'utf8_general_ci';
alter table 'telescope_entries' add unique 'telescope_entries_uuid_unique'('uuid');
alter table 'telescope_entries' add index 'telescope_entries_batch_id_index'('batch_id');
alter table 'telescope_entries' add index 'telescope_entries_family_hash_index'('family_hash');
alter table 'telescope_entries' add index 'telescope_entries_created_at_index'('created_at');
alter table 'telescope_entries' add index 'telescope_entries_type_should_display_on_index_index'('type', 'should_display_on_index');
create table 'telescope_entries_tags' ('entry_uuid' char(36) not null, 'tag' varchar(255) not null) default character set utf8 collate 'utf8_general_ci';
alter table 'telescope_entries_tags' add index 'telescope_entries_tags_entry_uuid_tag_index'('entry_uuid', 'tag');
alter table 'telescope_entries_tags' add index 'telescope_entries_tags_tag_index'('tag');
alter table 'telescope_entries_tags' add constraint 'telescope_entries_tags_entry_uuid_foreign' foreign key ('entry_uuid') references 'telescope_entries' ('uuid') on delete cascade;
create table 'telescope_monitoring' ('tag' varchar(255) not null) default character set utf8 collate 'utf8_general_ci';
create table 'failed_jobs' ('id' bigint unsigned not null auto_increment primary key, 'connection' text not null, 'queue' text not null, 'payload' longtext not null, 'exception' longtext not null, 'failed_at' timestamp default CURRENT_TIMESTAMP not null) default character set utf8 collate 'utf8_general_ci';
create table 'works' ('id' bigint unsigned not null auto_increment primary key, 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
create table 'work_reasons' ('id' bigint unsigned not null auto_increment primary key, 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
create table 'roles' ('id' bigint unsigned not null auto_increment primary key, 'name' varchar(255) not null, 'created_at' timestamp null, 'updated_at' timestamp null);
default character set utf8 collate 'utf8_general_ci';
create table 'role_user' ('role_id' bigint unsigned not null, 'user_id' bigint unsigned not null) default character set utf8 collate 'utf8_general_ci';
alter table 'role_user' add constraint 'role_user_role_id_foreign' foreign key ('role_id') references 'roles' ('id');
alter table 'role_user' add constraint 'role_user_user_id_foreign' foreign key ('user_id') references 'users' ('id');
create table 'permissions' ('id' bigint unsigned not null auto_increment primary key, 'route' varchar(255) not null, 'allow' tinyint(1) not null default '1', 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
create table 'jobs' ('id' bigint unsigned not null auto_increment primary key, 'queue' varchar(255) not null, 'payload' longtext not null, 'attempts' tinyint unsigned not null, 'reserved_at' int unsigned null, 'available_at' int unsigned not null, 'created_at' int unsigned not null) default character set utf8 collate 'utf8_general_ci';
alter table 'jobs' add index 'jobs_queue_index'('queue');
create table 'permission_role' ('permission_id' bigint unsigned not null, 'role_id' bigint unsigned not null) default character set utf8 collate 'utf8_general_ci';
alter table 'permission_role' add constraint 'permission_role_permission_id_foreign' foreign key ('permission_id') references 'permissions' ('id');
alter table 'permission_role' add constraint 'permission_role_role_id_foreign' foreign key ('role_id') references 'roles' ('id');
alter table 'roles' add 'slug' varchar(255) not null;
alter table 'users' add 'locale' varchar(255) not null default 'pt';
create table 'agents' ('id' bigint unsigned not null auto_increment primary key, 'name' varchar(255) not null, 'user_id' bigint unsigned null, 'external' tinyint(1) not null default '0', 'enabled' tinyint(1) not null default '0', 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
alter table 'agents' add constraint 'agents_user_id_foreign' foreign key ('user_id') references 'users' ('id');
alter table 'agents' add unique 'agents_user_id_unique'('user_id');
create table 'sessions' ('id' varchar(255) not null, 'user_id' bigint unsigned null, 'user_agent' text null, 'payload' text not null, 'last_activity' int not null) default character set utf8 collate 'utf8_general_ci';
alter table 'sessions' add unique 'sessions_id_unique'('id');
alter table 'users' add 'guid' varchar(255) null, add 'domain' varchar(255) null;
alter table 'users' add unique 'users_guid_unique'('guid');
create table 'delegations' ('id' bigint unsigned not null auto_increment primary key, 'designation' varchar(255) not null, 'deleted_at' timestamp null, 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
alter table 'users' add 'deleted_at' timestamp null;
alter table 'agents' add 'deleted_at' timestamp null;
alter table 'roles' add 'deleted_at' timestamp null;
create table 'failure_types' ('id' bigint unsigned not null auto_increment primary key, 'designation' varchar(255) not null, 'enabled' tinyint(1) not null default '1', 'deleted_at' timestamp null, 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
create table 'materials' ('id' bigint unsigned not null auto_increment primary key, 'designation' varchar(255) not null, 'failure_type_id' bigint unsigned not null, 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
alter table 'materials' add constraint 'materials_failure_type_id_foreign' foreign key ('failure_type_id') references 'materials' ('id');
create table 'report_items' ('id' bigint unsigned not null auto_increment primary key, 'numero_lancamento' int null, 'codigo_artigo' int not null, 'numero_obra' int not null, 'preco_unitario' decimal(9, 2) not null, 'quantidade' int not null, 'data_documento' date not null, 'user_id' bigint unsigned not null, 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
alter table 'report_items' add constraint 'report_items_user_id_foreign' foreign key ('user_id') references 'users' ('id');
create table 'reports' ('id' bigint unsigned not null auto_increment primary key, 'current_status' bigint unsigned not null, 'user_id' bigint unsigned not null, 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
alter table 'reports' add constraint 'reports_user_id_foreign' foreign key ('user_id') references 'users' ('id');
create table 'statuses' ('id' bigint unsigned not null auto_increment primary key, 'name' varchar(255) not null, 'slug' varchar(255) not null, 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
create table 'process_status' ('id' bigint unsigned not null auto_increment primary key, 'process_id' bigint unsigned not null, 'status_id' bigint unsigned not;
null, 'user_id' bigint unsigned not null, 'previous_status' bigint unsigned not null, 'failover_role' bigint unsigned not null, 'failover_user' bigint unsigned not null, 'concluded_at' timestamp not null, 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
alter table 'process_status' add constraint 'process_status_process_id_foreign' foreign key ('process_id') references 'reports' ('id');
alter table 'process_status' add constraint 'process_status_user_id_foreign' foreign key ('user_id') references 'users' ('id');
alter table 'process_status' add constraint 'process_status_status_id_foreign' foreign key ('status_id') references 'statuses' ('id');
alter table 'process_status' add constraint 'process_status_previous_status_foreign' foreign key ('previous_status') references 'process_status' ('id');
alter table 'process_status' add constraint 'process_status_failover_role_foreign' foreign key ('failover_role') references 'roles' ('id');
alter table 'process_status' add constraint 'process_status_failover_user_foreign' foreign key ('failover_user') references 'users' ('id');
create table 'role_status' ('role_id' bigint unsigned not null, 'status_id' bigint unsigned not null, 'created_at' timestamp null, 'updated_at' timestamp null) default character set utf8 collate 'utf8_general_ci';
alter table 'role_status' add constraint 'role_status_role_id_foreign' foreign key ('role_id') references 'roles' ('id');
alter table 'role_status' add constraint 'role_status_status_id_foreign' foreign key ('status_id') references 'statuses' ('id');

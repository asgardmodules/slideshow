<?php
class FaqMigration {
	public static function up() {
		$table = \Asgard\Core\App::get('config')->get('database/prefix').'slide';
		\Asgard\Core\App::get('schema')->create($table, function($table) {
			$table->add('id', 'int(11)')
				->autoincrement()
				->primary();	
			$table->add('created_at', 'datetime')
				->nullable();	
			$table->add('updated_at', 'datetime')
				->nullable();	
			$table->add('image', 'varchar(255)')
				->nullable();	
			$table->add('description', 'varchar(255)')
				->nullable();
		});
	}
	
	public static function down() {
		\Asgard\Core\App::get('schema')->drop(\Asgard\Core\App::get('config')->get('database/prefix').'slide');
	}
}
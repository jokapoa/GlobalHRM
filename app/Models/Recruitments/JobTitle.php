<?php

namespace App\Models\Recruitments;

use Illuminate\Database\Eloquent\Model;

class JobTitle extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table;
	/**
	 * The attributes that are not mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];
	
	/**
	 * Define our custom primary key
	 */
	protected $primaryKey;
	/*
	 * 
	 */
	protected $fillable;
	
	public function __construct()
	{
		$this->table      = "tblJobTitles";
		$this->primaryKey = "id";
		$this->fillable = [
				'name', 
				'description'
		];
	}
	
}

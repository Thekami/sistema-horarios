<?php
class Disponible extends Eloquent{
	public $timestamps = false;
	protected $table = 'horas_disponibles';
	protected $primaryKey = 'id';
}
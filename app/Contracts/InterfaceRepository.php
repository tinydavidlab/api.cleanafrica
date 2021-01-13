<?php


namespace App\Contracts;


use Illuminate\Http\Request;

interface InterfaceRepository
{
    public function all();

    public function save( Request $request );

    public function update( int $id, Request $request );

    public function delete( int $id );

    public function find( int $id );

}

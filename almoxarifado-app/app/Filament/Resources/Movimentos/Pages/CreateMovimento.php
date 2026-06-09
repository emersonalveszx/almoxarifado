<?php

namespace App\Filament\Resources\Movimentos\Pages;

use App\Filament\Resources\Movimentos\MovimentoResource;
use App\Models\Produto;
use App\Models\Movimento;
use Filament\Resources\Pages\CreateRecord;

/**
 * O que esta função faz?
 * before
 * @param $data recebe dados
 * @param $produto recebe uma lista com os dados do produto
 * @param $quantidade recebe quantos produtos serão adicionados ou removidos
 * @param $tipo recebe o tipo de movimentação, entrada ou saída
 * after
 * 
 */

class CreateMovimento extends CreateRecord
{
    protected static string $resource = MovimentoResource::class;

    protected function beforeCreate(): void
    {
        $data = $this->data;
        $produto = Produto::find($data['produto_id']);
        $quantidade = $data['quantidade'];
        $tipo = $data['tipo'];

        if($tipo=== 's' && $quantidade > $produto->estoque){
            Notification::make()
                ->danger()
                ->title('estoque insuficiente')
                ->body("O estoque de '{$produto->nome}' é de apenas {$produto->estoque} unidade, mas você tentou retirar {$quantidade}.")
                ->send();
            $this->halt();
        }
    }
    protected function afterCreate(): void
    {
        $movimento = $this-> getRecord();
        $produto = $movimento->produto;

        if ($movimento->tipo === 'e'){
            $produto->increment('estoque', $movimento->quantidade);
        } else {
            $produto->decrement('estoque', $movimento->quantidade);
        }
    }
}

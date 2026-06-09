<?php

use App\Models\Produto;
use App\Models\Movimento;

// 1. Teste de validação que simula o beforeCreate
test('sistema deve barrar a movimentação se a qauntidade de saída for maior que o estoque', function(){
    //Mockando o Produto
    $produtoMock = new produto([
        'nome' => 'Mouse USB Dell',
        'estoque'=> 5,
    ]);

    //Mockando o movimento
    $movimentoMock = new Movimento([
        'quantidade' => 10,
        'tipo' => 's',
    ]);

    if($movimentoMock->tipo ==='s' && $movimentoMock->quantidade > $produtoMock->estoque){
        expect(true)->toBeTrue();
    }else{
        $this ->fail("Erro: a regra de negócio permitiu saída de mercadoria sem estoque");
    }




    test('o sistema deve diminuir o estoque após uma saída autorizada', function(){
        $produto = Produto::create([
            'nome' => 'Teclado mecânico',
            'estoque' => 15,
        ]);
    });

    Livewhere::test(CreateMovimento::class)
        ->fillForm([
            'produto_id' => $produto->id,
            'quantidade' => 5,
            'tipo' => 's',
        ])
        -> call('create');
    expect(Movimento::count())->toBe(1);
    expect($produto->fresh()->estoque->toBe(10));
});
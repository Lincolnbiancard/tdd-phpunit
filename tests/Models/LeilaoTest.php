<?php

namespace TDD\Leilao\Tests\Model;

use TDD\Leilao\Model\Lance as Lance;
use TDD\Leilao\Model\Leilao as Leilao;
use TDD\Leilao\Model\Usuario as Usuario;

use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{
    public function testLeilaoNaoDeveAceitarMaisDe5LancesPorUsuario()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Limite de 5 lances por uisuário excedido");
        $leilao = new Leilao('Brasília Amarela');
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $leilao->recebeLance(new Lance($joao, 1000));
        $leilao->recebeLance(new Lance($maria, 1500));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 3000));
        $leilao->recebeLance(new Lance($maria, 3500));
        $leilao->recebeLance(new Lance($joao, 4000));
        $leilao->recebeLance(new Lance($maria, 4500));
        $leilao->recebeLance(new Lance($joao, 5000));
        $leilao->recebeLance(new Lance($maria, 5500));
        $leilao->recebeLance(new Lance($joao, 6000));
    }

    /**
     * @dataProvider  generatorBids
     */
    public function testLeilaoDeveReceberLance(
        int $quantityBids,
        Leilao $auction,
       array  $values
    ) {
        static::assertCount($quantityBids, $auction->getLances());

        foreach ($values as $index => $expectedValue) {
            static::assertEquals($expectedValue, $auction->getLances()[$index]->getValor());
        }
    }

    public function testLeilaoNaoDeveReceberLancesRepetidos()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Usuário não pode propor 2 lances consecutivos");
        $leilao = new Leilao('Corsa Ret 0km');
        $babi = new Usuario('Babi');

        $leilao->recebeLance(new Lance($babi, 1000));
        $leilao->recebeLance(new Lance($babi, 1500));
    }

    public function generatorBids(): array
    {
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');

        $auction2 = new Leilao('Fiat 147 0 km');
        $auction2->recebeLance(new Lance($maria,1900));
        $auction2->recebeLance(new Lance($joao, 2000));

        $auction = new Leilao('Audi A1 0 km');
        $auction->recebeLance(new Lance($joao, 50000));

        return [
            'Valor esperado com 2 lances' => [2, $auction2, [1900, 2000]],
            'Valor esperado com 1 lance' => [1, $auction, [50000]],
        ];
    }

    public function testLeilaoFinalizadoNaoPodeSerAvaliado()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Leilão finalizado não pode receber lances");
        $auction = new Leilao('Fiat 147 0 km');
        $auction->finished();
        $maria = new Usuario('Maria');
        $auction->recebeLance(new Lance($maria, 50000));
    }
}
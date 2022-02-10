<?php

namespace Alura\Leilao\Tests\Model;

use Alura\Leilao\Model\Lance as Lance;
use Alura\Leilao\Model\Leilao as Leilao;
use Alura\Leilao\Model\Usuario as Usuario;

use PHPUnit\Framework\TestCase;

class LeilaoTest extends TestCase
{

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
        $leilao = new Leilao('Corsa Ret 0km');
        $babi = new Usuario('Babi');

        $leilao->recebeLance(new Lance($babi, 1000));
        $leilao->recebeLance(new Lance($babi, 1500));

        static::assertCount(1, $leilao->getLances());
        static::assertEquals(1000, $leilao->getLances()[0]->getValor());
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
}
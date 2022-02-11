<?php

namespace TDD\Leilao\Tests\Service;

use TDD\Leilao\Model\Lance;
use TDD\Leilao\Model\Leilao;
use TDD\Leilao\Model\Usuario;
use TDD\Leilao\Service\Avaliador;
use PHPUnit\Framework\TestCase;

class AvaliadorTest extends TestCase
{
    private Avaliador $evaluator;

    protected function setUp(): void
    {
        $this->evaluator = new Avaliador();
    }

    /**
     * @dataProvider auctionAsc
     * @dataProvider auctionDesc
     * @dataProvider auctionSort
     */
    public function testAvaliadorDeveEncontrarMaiorLanceDoLeilao(Leilao $leilao)
    {
        // Arrange - Given
        $leiloeiro = $this->evaluator;

        // Act - When
        $leiloeiro->avalia($leilao);

        $highestValue = $leiloeiro->getHighestBid();

        // Assert - Then
        self::assertEquals(2400, $highestValue);
    }

    /**
     * @dataProvider auctionAsc
     * @dataProvider auctionDesc
     * @dataProvider auctionSort
     */
    public function testAvaliadorDeveEncontrarMenorLanceDoLeilao(Leilao $leilao)
    {
        // Arrange - Given
        $leiloeiro = $this->evaluator;

        // Act - When
        $leiloeiro->avalia($leilao);

        $lowerValue = $leiloeiro->getLowerBid();

        // Assert - Then
        self::assertEquals(1900, $lowerValue);
    }

    /**
     * @dataProvider auctionAsc
     * @dataProvider auctionDesc
     * @dataProvider auctionSort
     */
    public function testAvaliadorDeveBuscar3MaioresLances(Leilao $leilao)
    {
        // Arrange - Given
        $leiloeiro = $this->evaluator;
        $leiloeiro->avalia($leilao);

        $threeBiggestBids = $leiloeiro->getThreeBiggestBids();
        static::assertCount(3, $threeBiggestBids);
        static::assertEquals(2400, $threeBiggestBids[0]->getValor());
        static::assertEquals(2100, $threeBiggestBids[1]->getValor());
        static::assertEquals(2000, $threeBiggestBids[2]->getValor());
    }

    // DATA
    public function auctionAsc(): array
    {
        // Arrange - Given
        $auction = new Leilao('Fiat 147 0 km');
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $babi = new Usuario('Babi');
        $jorge = new Usuario('Jorge');

        $auction->recebeLance(new Lance($jorge,1900));
        $auction->recebeLance(new Lance($joao, 2000));
        $auction->recebeLance(new Lance($babi,2100));
        $auction->recebeLance(new Lance($maria,2400));

        return [
            'Ordem crescente' =>  [$auction]
        ];
    }

    public function auctionDesc(): array
    {
        // Arrange - Given
        $auction = new Leilao('Fiat 147 0 km');
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $babi = new Usuario('Babi');
        $jorge = new Usuario('Jorge');

        $auction->recebeLance(new Lance($maria,2400));
        $auction->recebeLance(new Lance($babi,2100));
        $auction->recebeLance(new Lance($joao, 2000));
        $auction->recebeLance(new Lance($jorge,1900));

        return [
            'Ordem decrescente' => [$auction]
        ];
    }

    public function auctionSort(): array
    {
        // Arrange - Given
        $auction = new Leilao('Fiat 147 0 km');
        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $babi = new Usuario('Babi');
        $jorge = new Usuario('Jorge');

        $auction->recebeLance(new Lance($babi,2100));
        $auction->recebeLance(new Lance($maria,2400));
        $auction->recebeLance(new Lance($jorge,1900));
        $auction->recebeLance(new Lance($joao, 2000));

        return [
            'Ordem aleatória' => [$auction]
        ];
    }

}
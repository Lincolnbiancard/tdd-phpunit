<?php

namespace TDD\Leilao\Service;

use TDD\Leilao\Model\Lance;
use TDD\Leilao\Model\Leilao;

class Avaliador
{
    private float $highestBid  = -INF;
    private  $lowerBid = INF;
    private array $threeBiggestBids;

    public function avalia(Leilao $leilao): void
    {
        foreach ($leilao->getLances() as $bid) {
            if( $bid->getValor() > $this->highestBid ) {
                $this->highestBid = $bid->getValor();
            }

            if ( $bid->getValor() < $this->lowerBid ) {
                $this->lowerBid = $bid->getValor();
            }
        }

        $bids = $leilao->getLances();
        usort($bids, function (Lance $lance1, Lance $lance2) {
            return  $lance2->getValor() - $lance1->getValor() ;
        });

        $this->threeBiggestBids = array_slice($bids, 0, 3);
    }

    public function getHighestBid(): float
    {
        return $this->highestBid;
    }

    public function getLowerBid():  float
    {
        return $this->lowerBid;
    }

    public function getThreeBiggestBids() : array
    {
        return $this->threeBiggestBids;
    }
}
<?php

use TDD\Leilao\Model\Lance as Lance;
use TDD\Leilao\Model\Leilao as Leilao;
use TDD\Leilao\Model\Usuario as Usuario;
use TDD\Leilao\Service\Avaliador as Avaliador;

require 'vendor/autoload.php';

// Arrange - Given
$leilao = new Leilao('Fiat 147 0 km');
$user1 = new Usuario('João');
$user2 = new Usuario('Maria');

$leilao->recebeLance(new Lance($user1, 2000));
$leilao->recebeLance(new Lance($user2,2400));

$leiloeiro = new Avaliador();

// Act - When
$leiloeiro->avalia($leilao);

$highestValue = $leiloeiro->getHighestBid();

// Assert - Then
$expectedValue = 2400;

if ($expectedValue == $highestValue) {
    echo "TESTE OK";
} else {
    echo "TESTE FALHOU";
}



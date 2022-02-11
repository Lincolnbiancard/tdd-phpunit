<?php

namespace TDD\Leilao\Model;

class Leilao
{
    private array $lances;
    private String $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {
        if (!empty($this->lances) && $this->belongsToTheLastUser($lance)) {
            return;
        }

        $usuario = $lance->getUsuario();
        $totalLancesUsuario = $this->quantityOfBidsPerUser($usuario);
        if ($totalLancesUsuario >= 5) {
            return;
        }

        $this->lances[] = $lance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    /**
     * @param Lance $bid
     * @return bool
     */
    public function belongsToTheLastUser(Lance $bid): bool
    {
        $lastBid = $this->lances[count($this->lances) - 1];
        return $bid->getUsuario() == $lastBid->getUsuario();
    }

    /**
     * @param $usuario
     * @return int
     */
    public function quantityOfBidsPerUser($usuario): int
    {
        return array_reduce(
            $this->lances,
            function (int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
                if ($lanceAtual->getUsuario() == $usuario) {
                    return $totalAcumulado + 1;
                }
                return $totalAcumulado;
            },
            0
        );
    }
}

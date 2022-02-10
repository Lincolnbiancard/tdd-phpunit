<?php

namespace Alura\Leilao\Model;

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
}

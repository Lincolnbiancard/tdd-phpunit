<?php

namespace TDD\Leilao\Model;

class Leilao
{
    private array $lances;
    private String $descricao;
    private string $status = "initiated";

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {
        if ($this->getStatus() === 'finished') {
            throw new \DomainException('Leilão finalizado não pode receber lances');
        }

        if (!empty($this->lances) && $this->belongsToTheLastUser($lance)) {
            throw new \DomainException('Usuário não pode propor 2 lances consecutivos');
        }

        $usuario = $lance->getUsuario();
        $totalLancesUsuario = $this->quantityOfBidsPerUser($usuario);
        if ($totalLancesUsuario >= 5) {
            throw new \DomainException('Limite de 5 lances por uisuário excedido');
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

    public function finished(): string
    {
        return $this->status = "finished";
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}

<?php
trait TieneFechas {
    private ?string $fechaCreacion = null;

    public function setFechaCreacion(?string $fecha): void {
        $this->fechaCreacion = $fecha;
    }

    public function getFechaCreacion(): ?string {
        return $this->fechaCreacion;
    }

    public function fechaFormateada(): string {
        if ($this->fechaCreacion === null) return '—';
        return date('d/m/Y', strtotime($this->fechaCreacion));
    }
}
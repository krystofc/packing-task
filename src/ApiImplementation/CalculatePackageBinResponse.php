<?php declare(strict_types=1);

namespace App\ApiImplementation;

class CalculatePackageBinResponse {

    private readonly ?Packaging $packaging;
    private readonly ?string $error;
    private readonly ?int $errorCode;

    public static function success(Packaging $packaging): self {
        $self = new self;
        $self->packaging = $packaging;
        return $self;
    }

    public static function error(string $error, int $code): self {
        $self = new self;
        $self->error = $error;
        $self->errorCode = $code;
        return $self;
    }

    public function getPackaging(): ?Packaging {
        return $this->packaging;
    }

    public function getError(): ?string {
        return $this->error;
    }

    public function getErrorCode(): ?int {
        return $this->errorCode;
    }
}

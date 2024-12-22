<?php

namespace Core\Session;

class Flash extends SessionManager
{
    public function success(string $message): Flash
    {
        $this->flash('success', $message);

        return $this;
    }

    public function error(string $message): Flash
    {
        $this->flash('error', $message);

        return $this;
    }

    public function warning(string $message): Flash
    {
        $this->flash('warning', $message);

        return $this;
    }

    public function flash(string $type, string $message): Flash
    {
        $template = sprintf('<div class="alert alert-%s mt-4" role="alert">%s</div>',$type, $message);
        $this->set('flash', $template);

        return $this;
    }

    public function render(): mixed
    {
        return $this->get('flash');
    }

    public function clear(): void
    {
        $this->delete('flash');
    }
}

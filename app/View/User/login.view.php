<div class="login__form">
    <form action="/api/users/login" method="POST">
        <?= getCsrf() ?>
        <div class="container mx-auto px-[200px] pt-20">
            <div class="login__wrap mx-auto px-[400px]">
                <label class="input input-bordered flex items-center gap-2">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="text" class="grow focus-visible:outline-none" placeholder="Email" name="email" />
                </label>

                <label class="input input-bordered flex items-center gap-2 mt-4">
                    <i class="fa-solid fa-key"></i>
                    <input type="password" class="grow focus-visible:outline-none" placeholder="Password" name="password" />
                </label>
                <button type="submit" class="btn btn-primary mt-4">Connexion</button>
            </div>
        </div>
    </form>
</div>

<style>
input {
    border: none;
}

[type='text'] {
  --tw-ring-color: transparent !important;
}

[type='password'] {
  --tw-ring-color: transparent !important;
}
</style>
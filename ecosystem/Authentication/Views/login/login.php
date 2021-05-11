<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>
    <?= var_dump(session()->getFlashdata()); ?>
    <?php
        // Open form
        $attributes = ['id' => 'login'];
        echo form_open('login/process', $attributes);
    ?>
        <div class="form-group">
            <input type="text" class="form-control" name="email" id="email" placeholder="Enter your email">
            <?= $validation->showError('email'); ?>
        </div>

        <div class="form-group mg-b-50">
            <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
        </div>
        <button type="submit" class="btn btn-primary btn-block btn-signin">Sign In</button>
        <p class="mg-b-0">Forgotten Password? <a href="<?= base_url('recover/forgot-password') ?>">Recover here</a></p>
    </form>
<?= $this->endSection() ?>
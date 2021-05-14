<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>
    <?php if (isset($user)): ?>
        <h2 class="signin-title-primary">Reset Password!</h2>
        <h3 class="signin-title-secondary">Set a new Password.</h3>
        <?= var_dump(session()->getFlashdata()); ?>

        <?php
            // Open form
            $attributes = ['id' => 'reset-passsword'];
            echo form_open(route_to('reset_password', $token), $attributes);
        ?>

            <div class="form-group mg-b-20">
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
                <?= $validation->showError('password'); ?>
            </div>

            <div class="form-group mg-b-30">
                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm your password">
                <?= $validation->showError('confirm_password'); ?>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-signin">Change Password</button>
        </form>

    <?php else: ?>
        <div class="text-center">
            <h2 class="text-danger signin-title-secondary">Reset Failed...</h2>

            <p class="lead">Your password reset isn't possible at the moment</p>
            <hr>
            
            <p>Kindly Request another reset <a href="/recover/forgot-password">here</a></p>
            <p>Contact our support team at support@biofem.org</p>
        </div>
    <?php endif; ?>

<?= $this->endSection() ?>
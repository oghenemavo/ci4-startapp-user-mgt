<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>
    <?php
        // Open form
        $attributes = ['id' => 'signup'];
        echo form_open('signup/process', $attributes);
    ?>

        <div class="form-group">
            <label for="firstname">FIRST NAME</label>
            <input type="text" class="form-control" name="firstname" id="firstname" value="<?= set_value('firstname') ?>" placeholder="FIRST NAME" autofocus>
            <?= $validation->showError('firstname'); ?>
        </div>
        
        <div class="form-group">
            <label for="lastname">LAST NAME</label>
            <input type="text" class="form-control" name="lastname" id="lastname" value="<?= set_value('lastname') ?>" placeholder="LAST NAME">
            <?= $validation->showError('lastname'); ?>
        </div>

        <div class="form-group">
            <label for="phone">PHONE NUMBER</label>
            <input type="tel" class="form-control" name="phone" id="phone" value="<?= set_value('phone') ?>" placeholder="PHONE NUMBER">
            <?= $validation->showError('phone'); ?>
        </div>

        <div class="form-group">
            <label for="email">EMAIL</label>
            <input type="email" class="form-control" name="email" id="email" value="<?= set_value('email') ?>" placeholder="EMAIL">
            <?= $validation->showError('email'); ?>
        </div>
        
        <div class="form-group">
            <label for="password">PASSWORD</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="PASSWORD">
            <?= $validation->showError('password'); ?>
        </div>

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="terms" name="terms">
            <label class="custom-control-label small" for="terms">Terms of service</label>
            <?= $validation->showError('terms'); ?>
        </div>

        <div class="small py-1">Have an account? <a class="" href="<?= base_url('login') ?>">log in</a></div>

        <button type="submit" class="btn btn-sm btn-primary">Create account</button>
    </form>

<?= $this->endSection() ?>
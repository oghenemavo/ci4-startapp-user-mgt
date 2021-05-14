<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>

<h4>Email</h4>

<?php
    // Open form
    $attributes = ['id' => 'update-email'];
    echo form_open(route_to('change_email'), $attributes);
?>

    <div class="form-group">
        <label for="email" class="sr-only sr-only-focusable">EMAIL</label>
        <input type="text" class="form-control w-75" name="email" id="email" value="<?= $user->user_email ?>" placeholder="EMAIL">
        <?= $validation->showError('email'); ?>
    </div>

    <div class="btn-demo my-3">
        <button type="submit" class="btn btn-indigo active mg-b-10">Change Email</button>
    </div>

<?php
    // Close form
    echo form_close();
?>

<h4>Password</h4>

<?php
    // Open form
    $attributes = ['id' => 'update-password'];
    echo form_open(route_to('change_password'), $attributes);
?>

    <div class="form-group">
        <label for="password" class="sr-only sr-only-focusable">CURRENT PASSWORD</label>
        <input type="password" class="form-control w-75" name="password" id="password" placeholder="CURRENT PASSWORD">
        <?= $validation->showError('password'); ?>
    </div>

    <div class="form-group">
        <label for="password" class="sr-only sr-only-focusable">NEW PASSWORD</label>
        <input type="password" class="form-control w-75" name="new_password" id="new_password" placeholder="NEW PASSWORD">
        <?= $validation->showError('new_password'); ?>
    </div>

    <div class="form-group">
        <label for="confirm_new_password" class="sr-only sr-only-focusable">REPEAT NEW PASSWORD</label>
        <input type="password" class="form-control w-75" name="confirm_new_password" id="confirm_new_password" placeholder="REPEAT NEW PASSWORD">
        <?= $validation->showError('confirm_new_password'); ?>
    </div>

    <div class="btn-demo my-3">
        <button type="submit" class="btn btn-indigo active mg-b-10">Change Password</button>
    </div>
</form>

<?= $this->endSection() ?>
<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>
    <?= var_dump(session()->getFlashdata()); ?>

    <?php
        // Open form
        $attributes = ['id' => 'create-user'];
        echo form_open(base_url(route_to('create_user')), $attributes);
    ?>

        <div class="form-group">
            <label for="firstname" class="sr-only sr-only-focusable">FIRST NAME</label>
            <input type="text" class="form-control" name="firstname" id="firstname" value="<?= set_value('firstname') ?>" placeholder="FIRST NAME" >
            <?= $validation->showError('firstname'); ?>
        </div>
        <div class="form-group">
            <label for="lastname" class="sr-only sr-only-focusable">LAST NAME</label>
            <input type="text" class="form-control" name="lastname" id="lastname" value="<?= set_value('lastname') ?>" placeholder="LAST NAME" >
            <?= $validation->showError('lastname'); ?>
        </div>

        <div class="custom-control custom-radio">
            <input type="radio" id="male" name="gender" class="custom-control-input" value="Male" >
            <label class="custom-control-label" for="male">Male</label>
        </div>

        <div class="custom-control custom-radio">
            <input type="radio" id="female" name="gender" class="custom-control-input" value="Female" >
            <label class="custom-control-label" for="female">Female</label>
            <?= $validation->showError('gender'); ?>
        </div>

        <div class="form-group">
            <label for="phone" class="sr-only sr-only-focusable">PHONE NUMBER</label>
            <input type="tel" class="form-control" name="phone" id="phone" value="<?= set_value('phone') ?>" placeholder="PHONE NUMBER" >
            <?= $validation->showError('phone'); ?>
        </div>

        <div class="form-group">
            <label for="email" class="sr-only sr-only-focusable">EMAIL</label>
            <input type="email" class="form-control" name="email" id="email" value="<?= set_value('email') ?>" placeholder="EMAIL" >
            <?= $validation->showError('email'); ?>
        </div>

        <div class="form-group">
            <label for="password" class="sr-only sr-only-focusable">PASSWORD</label>
            <input type="password" class="form-control" name="password" id="password" value="" placeholder="PASSWORD" >
            <?= $validation->showError('password'); ?>
        </div>

        <select name="role">
            <option value="">Select Role</option>
            <?php foreach ($roles as $role) : ?>
                <option value="<?= $role->id ?>">
                    <?= $role->role ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="active" name="active" >
            <label class="custom-control-label small" for="active">Active</label>
            <?= $validation->showError('active'); ?>
        </div>

        <button type="submit">Create User</button>

    </form>

<?= $this->endSection() ?>
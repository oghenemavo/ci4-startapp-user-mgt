<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>

<?php
    // Open form
    $attributes = ['id' => 'update-user'];
    echo form_open(base_url(route_to('update_user', $user->id)), $attributes);
?>

    <div class="form-group">
        <label for="firstname" class="sr-only sr-only-focusable">FIRST NAME</label>
        <input type="text" class="form-control" name="firstname" id="firstname" value="<?= $user->first_name ?>" placeholder="FIRST NAME" <?php if ($is_same_superadmin) echo 'disabled'; ?>>
        <?= $validation->showError('firstname'); ?>
    </div>
    <div class="form-group">
        <label for="lastname" class="sr-only sr-only-focusable">LAST NAME</label>
        <input type="text" class="form-control" name="lastname" id="lastname" value="<?= $user->last_name ?>" placeholder="LAST NAME" <?php if ($is_same_superadmin) echo 'disabled'; ?>>
        <?= $validation->showError('lastname'); ?>
    </div>

    <div class="custom-control custom-radio">
        <input type="radio" id="male" name="gender" class="custom-control-input" value="Male" <?= $user->user_gender == 'Male' ? 'checked' : '' ?> <?php if ($is_same_superadmin) echo 'disabled'; ?> >
        <label class="custom-control-label" for="male">Male</label>
    </div>
    <div class="custom-control custom-radio">
        <input type="radio" id="female" name="gender" class="custom-control-input" value="Female" <?= $user->user_gender == 'Female' ? 'checked' : '' ?> <?php if ($is_same_superadmin) echo 'disabled'; ?> >
        <label class="custom-control-label" for="female">Female</label>
        <?= $validation->showError('gender'); ?>
    </div>

    <div class="form-group">
        <label for="phone" class="sr-only sr-only-focusable">PHONE NUMBER</label>
        <input type="tel" class="form-control" name="phone" id="phone" value="<?= $user->phone_number ?>" placeholder="PHONE NUMBER"  <?php if ($is_same_superadmin) echo 'disabled'; ?> >
        <?= $validation->showError('phone'); ?>
    </div>

    <div class="form-group">
        <label for="email" class="sr-only sr-only-focusable">EMAIL</label>
        <input type="email" class="form-control" name="email" id="email" value="<?= $user->user_email ?>" placeholder="EMAIL" <?php if ($is_same_superadmin) echo 'disabled'; ?> >
        <?= $validation->showError('email'); ?>
    </div>

    <div class="form-group">
        <label for="password" class="sr-only sr-only-focusable">PASSWORD</label>
        <input type="text" class="form-control" name="password" id="password" placeholder="PASSWORD" <?php if ($is_same_superadmin) echo 'disabled'; ?> >
        <div>Leave blank to keep current password</div>
        <?= $validation->showError('password'); ?>
    </div>

    <?= $user->role; ?>

    <div class="form-group">
        <?php if (($is_same_user && $is_superadmin) || $is_superadmin): // if user is editing his profile and a super admin || super admin editing super admin disable this  ?>
            <select name="role" disabled>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role->id ?>" <?= $user->role_id == $role->id ? 'selected' : '' ?> >
                        <?= $role->role ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php else: ?>
            <select name="role">
                <?php foreach ($roles as $role): ?>
                    <?php if ($is_superadmin && $is_checker_superadmin): // if edited user is super admin && editor is super admin  ?>
                        <option value="<?= $role->id ?>" <?= $user->role_id == $role->id ? 'selected' : '' ?>>
                            <?= $role->role ?>
                        </option>
                    <?php endif; ?>

                    <?php if (!$is_checker_superadmin && strtolower($role->role_slug) != strtolower('super_admin')): // if edited is not super admin and editor is not super ?>
                        <option value="<?= $role->id ?>" <?= $user->role_id == $role->id ? 'selected' : '' ?>>
                            <?= $role->role ?>
                        </option>
                    <?php else: ?>
                        <option value="<?= $role->id ?>" <?= $user->role_id == $role->id ? 'selected' : '' ?>>
                            <?= $role->role ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <?= $validation->showError('role'); ?>
    </div>

    <div class="custom-control custom-checkbox">
        <?php if ($is_same_user || $is_superadmin): ?>
            <input type="checkbox" class="custom-control-input" id="active" name="active" <?= $user->is_active == '1' ? 'checked' : '' ?> disabled>
        <?php else: ?>
            <input type="checkbox" class="custom-control-input" id="active" name="active" <?= $user->is_active == '1' ? 'checked' : '' ?>>
        <?php endif; ?>
        <label class="custom-control-label small" for="active">Active</label>
        <?= $validation->showError('active'); ?>
    </div>
    
    <button type="submit">Update</button>

</form>

<?= $this->endSection() ?>
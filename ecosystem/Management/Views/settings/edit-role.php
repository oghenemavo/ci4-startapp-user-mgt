
<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>
    <?= var_dump(session()->getFlashdata()); ?>
    <div>
        <?php
            $is_disabled = strtolower($role->role_slug) == strtolower('super_admin') ? 'disabled' : '';
            // Open form
            $attributes = ['id' => 'edit-role'];
            echo form_open(route_to('process_edit_role', $role->id), $attributes);
        ?>

        <div class="form-group">
            <label for="role" class="sr-only sr-only-focusable">ROLE</label>
            <input type="text" class="form-control" name="role" id="role" value="<?= $role->role ?>" placeholder="ROLE" <?= $is_disabled ?> >
            <?= $validation->showError('role'); ?>
        </div>

        <input type="hidden" name="id" value="<?= $role->id ?>">

        <div class="form-group mt-3">
            <label for="activate" class="ckbox">
                <input type="checkbox" id="activate" name="activate" <?= $role->is_active ? 'checked' : '' ?> <?= $is_disabled ?> >
                <span>Activate Role</span>
            </label>
        </div>

        <button type="submit" class="btn btn-purple active mg-b-10" <?= $is_disabled ?> >Edit Role</button>

        </form>

    </div><!-- section-wrapper -->

<?= $this->endSection() ?>
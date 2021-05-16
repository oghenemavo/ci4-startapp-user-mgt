<?php
use CodeIgniter\I18n\Time;
?>
<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>

        <div>
            <?php if ($user_lib->has_privilege(['add_role'])): ?>

                <?php
                    // Open form
                    $attributes = ['id' => 'add-role'];
                    echo form_open(route_to('create_role'), $attributes);
                ?>

                    <div class="form-group">
                        <label for="role" class="sr-only sr-only-focusable">ROLE NAME</label>
                        <input type="text" class="form-control" name="role" id="role" value="<?= set_value('role') ?>" placeholder="ROLE NAME">
                        <?= $validation->showError('role', 'custom_single'); ?>
                    </div>

                    <div class="form-group mt-3">
                        <label for="activate" class="ckbox">
                            <input type="checkbox" id="activate" name="activate">
                            <span>Activate Role</span>
                        </label>
                    </div>

                    <div class="btn-demo my-3">
                        <button type="submit" class="btn btn-indigo active mg-b-10">Create Role</button>
                    </div>

                </form>

            <?php endif; ?>

            <table id="listRoles" class="table">
                <thead>
                    <tr>
                        <th>s/no</th>
                        <th>Role</th>
                        <th>Activated</th>
                        <th>Created at</th>

                        <?php if ($user_lib->has_privilege('edit_role')): ?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach ($roles as $role): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $role->role ?></td>
                            <td><?= $role->is_active ? '&#10004;' : '&#10008;'; ?></td>
                            <td><?= Time::parse($role->created_at)->toLocalizedString('MMM d, yyyy') ?></td>

                            <?php if ($user_lib->has_privilege('edit_role')): ?>
                                <td>
                                    <?php if($role->role == 'Super Admin'): ?>
                                        <a href="#">Can't Edit</a>
                                    <?php else: ?>
                                        <a href="<?= base_url(route_to('edit_role', $role->id)) ?>">Edit Role</a>
                                    <?php endif; ?>
                                    <a href="<?= base_url(route_to('view_role_permission', $role->id)) ?>">Set Role Permission</a>
                                </td>
                                
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div><!-- section-wrapper -->

<script>
    // use chrome to view the dialog
    (() => {
        let dialog = document.getElementById('window');
        document.getElementById('show').addEventListener('click', () => dialog.show());
        document.getElementById('exit').addEventListener('click', () => dialog.close());
    })();
</script>

<?= $this->endSection() ?>
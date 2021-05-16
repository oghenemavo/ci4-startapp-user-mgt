<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>


    <div class="section-wrapper mg-t-20">
        <?php
            // Open form
            $attributes = ['id' => 'edit-pg'];
            echo form_open(route_to('edit_pg', $group->id), $attributes);
        ?>

            <div class="form-group">
                <label for="role" class="sr-only sr-only-focusable">GROUP NAME</label>
                <input type="text" class="form-control w-75" name="group" id="group" value="<?= $group->group_name ?>" placeholder="GROUP NAME">
                <?= $validation->showError('group'); ?>
            </div>

            <input type="hidden" name="id" value="<?= $group->id ?>">

            <div class="btn-demo my-3">
                <button type="submit" class="btn btn-purple active mg-b-10">Edit Group</button>
            </div>

        </form>

    </div><!-- section-wrapper -->

<?= $this->endSection() ?>
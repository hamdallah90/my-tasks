<template>
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
      <h1>Tasks</h1>
      <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
          <i class="bi bi-plus-lg"></i> Add Task
        </button>
      </div>
    </div>
    <div class="mb-3 d-flex align-items-center">
      <label for="projectFilter" class="form-label">Filter by Project</label>
      <select v-model="selectedProject" class="form-select me-2">
        <option value="">All Projects</option>
        <option v-for="project in projects" :key="project.id" :value="project.id">
          {{ project.name }}
        </option>
      </select>
      <button type="button" class="btn btn-outline-secondary" @click="selectedProject = ''">
        Clear Filter
      </button>
      <button type="button" class="btn btn-outline-secondary ms-2" @click="openCreateProjectModal">
        <i class="bi bi-plus-lg"></i> Add Project
      </button>
    </div>

    <ul id="tasks-list" class="list-group">
      <li v-for="task in filteredTasks" :key="task.id" :data-id="task.id"
        class="list-group-item d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <i class="bi bi-grip-vertical me-2" style="cursor: grab;"></i>
          <span>{{ task.name }}
            <span v-if="task.project_id" class="badge bg-secondary me-2">{{ task.project.name }}</span>
            <span class="text-muted">(Priority: {{ task.priority }})</span></span>
        </div>
        <div>
          <button type="button" class="btn btn-sm btn-outline-secondary me-1" @click="editTask(task)">
            <i class="bi bi-pencil-square"></i> Edit
          </button>
          <button type="button" class="btn btn-sm btn-outline-danger" @click="confirmDelete(task)">
            <i class="bi bi-trash"></i> Delete
          </button>
        </div>
      </li>
      <li v-if="filteredTasks.length === 0" class="list-group-item text-center">
        No tasks found for the selected project.
      </li>
    </ul>

    <!-- Create/Edit Task Modal -->
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-light">
            <h5 class="modal-title" id="taskModalLabel">{{ editingTask ? 'Edit Task' : 'Create Task' }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form @submit.prevent="saveTask">
            <div class="modal-body">
              <div class="mb-3">
                <label for="taskName" class="form-label">Task Name</label>
                <input type="text" class="form-control" id="taskName" v-model="form.name" required>
              </div>
              <div class="mb-3">
                <label for="taskPriority" class="form-label">Priority</label>
                <input type="number" class="form-control" id="taskPriority" v-model="form.priority">
              </div>
              <div class="mb-3">
                <label for="taskProject" class="form-label">Project</label>
                <select class="form-select" id="taskProject" v-model="form.project_id">
                  <option :value="null">No Project</option>
                  <option v-for="project in projects" :key="project.id" :value="project.id">
                    {{ project.name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">{{ editingTask ? 'Update' : 'Save' }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Create Project Modal -->
    <div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="projectModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-light">
            <h5 class="modal-title" id="projectModalLabel">Create Project</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form @submit.prevent="saveProject">
            <div class="modal-body">
              <div class="mb-3">
                <label for="projectName" class="form-label">Project Name</label>
                <input type="text" class="form-control" id="projectName" v-model="projectForm.name" required>
              </div>
            </div>
            <div class="modal-footer bg-light">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>


    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-warning bg-opacity-25">
            <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete task "{{ taskToDelete ? taskToDelete.name : '' }}"?
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" @click="deleteTask">Delete</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import Sortable from 'sortablejs';
import { router, useForm } from '@inertiajs/vue3';
import * as bootstrap from 'bootstrap';

const props = defineProps({
  tasks: Array,
  projects: Array,
});

const tasksList = ref([...props.tasks]); // Create a local ref for sorting
const editingTask = ref(null);
const taskToDelete = ref(null);
const selectedProject = ref('');

const form = useForm({
  id: null,
  name: '',
  priority: null,
  project_id: null, // Add project_id to the form
});

const projectForm = useForm({
  name: '',
});

const filteredTasks = computed(() => {
  if (!selectedProject.value) {
    return tasksList.value;
  }
  return tasksList.value.filter(task => task.project_id == selectedProject.value);
});

const openCreateModal = () => {
  editingTask.value = null;
  form.reset();
};

const openCreateProjectModal = () => {
  const modal = new bootstrap.Modal(document.getElementById('createProjectModal'));
  modal.show();
};

const saveProject = () => {
  projectForm.post(`/projects`, {
    onSuccess: (response) => {
      const modal = bootstrap.Modal.getInstance(document.getElementById('createProjectModal'));
      modal.hide();
      projects.value = [...projects.value, response.props.project]; // Assuming your backend returns the created project
      projectForm.reset();
    },
  });
};


const editTask = (task) => {
  editingTask.value = task;
  form.reset();

  Object.keys(task).forEach((key) => {
    if (key in form) {
      form[key] = task[key];
    }
  });

  const modal = new bootstrap.Modal(document.getElementById('createTaskModal'));
  modal.show();
};

const saveTask = () => {
  if (editingTask.value) {
    form.put(`/tasks/${editingTask.value.id}`, {
      onSuccess: () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('createTaskModal'));
        modal.hide();
        tasksList.value = tasksList.value.map(task => task.id === editingTask.value.id ? { ...task, ...form } : task);
      },
    });
  } else {
    form.post(`/tasks`, {
      onSuccess: (response) => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('createTaskModal'));
        modal.hide();
        tasksList.value = [...tasksList.value, response.props.task]; // Assuming your backend returns the created task
        form.reset();
      },
    });
  }
};

const confirmDelete = (task) => {
  taskToDelete.value = task;
  const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
  modal.show();
};

const deleteTask = () => {
  router.delete(`/tasks/${taskToDelete.value.id}`, {
    onSuccess: () => {
      const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
      modal.hide();
      tasksList.value = tasksList.value.filter(task => task.id !== taskToDelete.value.id);
      taskToDelete.value = null;
    },
  });
};

onMounted(() => {
  const el = document.getElementById('tasks-list');
  Sortable.create(el, {
    handle: '.list-group-item',
    onEnd: function (/**Event*/ evt) {
      const newOrder = Array.from(el.children).map((item) =>
        parseInt(item.dataset.id)
      );
      router.put(`tasks/update-priorities`, { taskOrder: newOrder });
    },
  });
});
</script>

<style scoped>
.list-group-item {
  cursor: grab;
  border: 1px solid #e0e0e0;
  margin-bottom: 0.5rem;
  border-radius: 0.25rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.list-group-item:hover {
  background-color: #f8f9fa;
}

.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
}

.btn-primary:hover {
  background-color: #0069d9;
  border-color: #0062cc;
}

.form-control,
.form-select {
  border-radius: 0.2rem;
}

.modal-header {
  border-bottom: 1px solid #e9ecef;
}

.modal-footer {
  border-top: 1px solid #e9ecef;
}
</style>
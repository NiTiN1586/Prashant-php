<?php

declare(strict_types=1);

namespace Jagaad\WitcherApi\Security\Enum\Permission;

enum Task: string
{
    case VIEW_ASSIGNED_TASKS = 'VIEW_ASSIGNED_TASKS';
    case VIEW_ALL_TASKS = 'VIEW_ALL_TASKS';
    case CREATE_TASKS = 'CREATE_TASKS';
    case UPDATE_ASSIGNED_TASKS = 'UPDATE_ASSIGNED_TASKS';
    case UPDATE_ALL_TASKS = 'UPDATE_ALL_TASKS';
    case DELETE_ASSIGNED_TASKS = 'DELETE_ASSIGNED_TASKS';
    case DELETE_ALL_TASKS = 'DELETE_ALL_TASKS';
}

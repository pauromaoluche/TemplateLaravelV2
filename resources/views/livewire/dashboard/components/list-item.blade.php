<div class="shadow-lg mb-5 bg-body-tertiary rounded border-left-primary" style="background-color: white">
    @if ($route !== 'menualias')
        <div class="btn d-flex justify-content-end">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="{{ route($route . '.create') }}" wire:navigate type="button" class="btn btn-success">Adicionar</a>
                @can('admin')
                    <button type="button" class="btn btn-danger" wire:click="confirmDeleteSelected">Remover
                        Selecionados</button>
                @endcan
            </div>
        </div>
    @endif
    <table class="table table-hover table-striped table-bordered ">
        <thead class="table-primary">
            <th scope="col">#</th>
            @foreach ($columns as $column)
                <th scope="col" style="width: {{ $column['width'] }}">{{ __('columns.' . $column['column']) }}</th>
            @endforeach
            <th class="text-center" scope="col">Ação</th>
        </thead>
        <tbody>
            <tr>
                <th scope="row">
                    <div class="form-check">
                        <input class="form-check-input all-inputs" type="checkbox" wire:model.live="selectAll">
                    </div>
                </th>
                <td><input class="form-control me-2" type="search" placeholder="Pesquisar" aria-label="Search"
                        id="search">
                </td>
            </tr>
            @foreach ($data as $item)
                <tr class="data-row">
                    <th scope="row">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $item->id }}"
                                wire:model.live="selectedItems">
                        </div>
                    </th>
                    @foreach ($columns as $column)
                        @if ($column['column'] == 'icon')
                            <td><i style="font-size: 35px" class="bi {{ $item->{$column['column']} }}"></i></td>
                        @else
                            <td class="table-item">{{ $item->{$column['column']} }}</td>
                        @endif
                    @endforeach
                    <td class="text-center">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="#" type="button" class="btn btn-outline-success">Editar</a>
                            <button type="button" class="btn btn-outline-danger"
                                wire:click="confirmDelete({{ $item->id }})">Excluir</button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

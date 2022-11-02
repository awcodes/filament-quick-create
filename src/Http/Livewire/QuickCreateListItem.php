<?php

namespace FilamentQuickCreate\Http\Livewire;

use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\CreateAction;
use Filament\Pages\Concerns\HasActions;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;

class QuickCreateListItem extends Component
{
    public string $resourceName;

    private Resource $resource;

    public Action | CreateAction $action;

    public function mount($resourceName)
    {
        $this->resource = App::make($resourceName);
        $listResource = invade(App::make($this->resource->getPages()['index']['class']));

        $this->action = $listResource->getCreateAction();

    }

    protected function configureAction(): void
    {
        $listResource = invade(App::make($this->resource->getPages()['index']['class']));

        dd($listResource->getCreateAction());

//        if ($this->resource->hasPage('create')) {
//            dd($this->resource);
//            $action = Action::make('create')->url(fn (): string => $this->resource->getUrl('create'));
//        } else {
//            $action = CreateAction::make()
//                ->authorize($this->resource->canCreate())
//                ->model($this->resource->getModel())
//                ->modelLabel($this->resource->getModelLabel())
//                ->form($listResource->getCreateFormSchema());
//        }
//
//        dd($action);
//
//
//
//        $this->mountedAction = $action;
    }

    public function render(): View
    {
        return view('filament-quick-create::components.list-item');
    }
}
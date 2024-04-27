<?php

namespace App\Http\Livewire;

use App\Models\BlogCategory;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;


class PostCrud extends Component
{
    use WithFileUploads;
     use WithPagination;

    public  $categories, $post_title, $post_content, $post_image, $post_status, $post_date, $meta_description, $meta_title, $meta_keywords, $category_id, $postId;
    public $isModalOpen = 0;

    public $search = '';
    protected $listeners = ['render','delete','deleteMultiple']; 
    
    public $perPage = 10; 

    
    public $sortBy = 'posts.id'; // Columna predeterminada para ordenar
    public $sortDirection = 'desc'; // Dirección predeterminada para ordenar

    public $selectAll = false;
public $checkedSelected = [];

public $newImage;
    public $oldImage;

    public function authorize()
{
    return true;
}

    public function render()
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('Admin')) {
            abort(403, 'This action is Forbidden.');
        }
           $this->authorize('manage admin');
         $this->categories = BlogCategory::latest()->get();
  
             $posts = Post::with('category')
            ->whereHas('category', function($query) {
                $query->where('blog_category_name', 'like', '%'.$this->search.'%');
            })
            ->orWhere('post_content', 'like', '%'.$this->search.'%')
            ->orWhere('post_title', 'like', '%'.$this->search.'%')
             ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    
    return view('livewire.post-crud', ['posts' => $posts]);
    }

    // Método para cambiar la cantidad de elementos por página
    public function updatedPerPage()
    {
        $this->resetPage(); // Resetear la página al cambiar la cantidad de elementos por página
    }

//----------- ordering columns start --------------//
public function sortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortBy = $column;
    }
    
     //----------- end ordering columns --------------//


    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->post_title = '';
        $this->post_content = '';
        $this->post_image = '';
        $this->newImage = '';
        $this->oldImage = '';
        $this->post_status = '';
        $this->post_date = '';
        $this->meta_description = '';
        $this->meta_title = '';
        $this->meta_keywords = '';
        $this->post_title_slug = '';
        $this->category_id = '';
        $this->postId = '';
    }

    public function store()
    {
        $validatedData = $this->validate([
            'post_title' => 'required|string|min:5|unique:posts,post_title,' . $this->postId,
            'post_content' => 'required|min:5',
             'newImage' => $this->postId ? 'nullable' : 'required',
            'post_status' => 'required',
            'meta_description' => 'required|min:5',
            'meta_title' => 'required|min:5',
            'meta_keywords' => 'required|min:1',
            
            'category_id' => 'required',
        ]);

       
        // CARBON FORMAT DATE
         $date = Carbon::now()->locale('en')->isoFormat('dddd, MMMM Do YYYY, H:mm A');
        // END CARBON FORMAT DATE

        // Si se proporciona un nuevo archivo, eliminar el anterior y guardar el nuevo
 // Si se proporciona un nuevo archivo, eliminar el anterior y guardar el nuevo
 $post_image = $this->oldImage;
if ($this->newImage && $this->newImage !== $this->oldImage) {
 
    
    // Lógica para guardar la nueva imagen
    if ($this->newImage->getMimeType() && (strpos($this->newImage->getMimeType(), 'image') !== false)) {
        
        // Eliminar la imagen anterior si existe
           
        if ($this->oldImage) {
        Storage::disk('public')->delete($this->oldImage);
         }

    // Es una imagen, entonces aplicar la lógica de carga con Intervention Image
        $image = $this->newImage->store('posts', 'public');
        // Crear un thumbnail de la imagen usando Intervention Image Library
        $imageHashName = $this->newImage->hashName();

        // Usar ImageManager para redimensionar la imagen si es necesario
        $resize = new ImageManager();
        $imageInstance = $resize->make('storage/posts/'.$imageHashName);

        // Verificar si es necesario redimensionar
        if ($imageInstance->width() > 700 || $imageInstance->height() > 700) {
            // Calcular el factor de escala para mantener la relación de aspecto
            $scaleFactor = min(700 / $imageInstance->width(), 700 / $imageInstance->height());

            // Calcular el nuevo ancho y alto para redimensionar la imagen
            $newWidth = $imageInstance->width() * $scaleFactor;
            $newHeight = $imageInstance->height() * $scaleFactor;

            // Redimensionar la imagen
            $imageInstance->resize($newWidth, $newHeight);
        }

        // Guardar la imagen redimensionada
        $imageInstance->save('storage/posts/'.$imageHashName);

        // Asignar la ruta de la imagen procesada
        $post_image = $image;
    } elseif ($this->newImage->getMimeType() && (strpos($this->newImage->getMimeType(), 'video') !== false)) {
        // Si es un video, simplemente guardarlo en el almacenamiento
        $post_image = $this->newImage->store('videos', 'public');
        // Puedes agregar cualquier lógica adicional necesaria para manejar videos
    }
} else {
    // Si no se proporciona una nueva imagen, mantener la imagen existente
    $post_image = $this->oldImage;
}



        Post::updateOrCreate(['id' => $this->postId], [
            'post_title' => $this->post_title,
            'post_content' => nl2br($this->post_content),
            'post_image' => ($this->newImage && $this->newImage !== $this->oldImage) ? $post_image : $this->oldImage,

            'post_status' => $this->post_status,
            'post_date' => $date,
            'meta_description' => $this->meta_description,
            'meta_title' => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'post_title_slug' => Str::slug($this->post_title),
            'category_id' => $this->category_id,
            'user_id' => auth()->id(),
        ]);

        session()->flash('message',
            $this->postId ? 'Post Updated Successfully.' : 'Post Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
        
         $this->CleanUp();
    }



    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $this->postId = $id;
        $this->post_title = $post->post_title;
        $this->post_content = $post->post_content;
         $this->oldImage = $post->post_image;
        $this->post_status = $post->post_status;
        $this->post_date = $post->post_date;
        $this->meta_description = $post->meta_description;
        $this->meta_title = $post->meta_title;
        $this->meta_keywords = $post->meta_keywords;
        $this->post_title_slug = $post->post_title_slug;
        $this->category_id = $post->category_id;
        $this->openModal();
    }


    
    public function delete($id)
{
    $this->authorize('manage admin');

    // Obtener la publicación que se va a eliminar
    $post = Post::find($id);
     $post_title = $post->post_title;
    // Verificar si la publicación tiene una imagen asociada
    if ($post->post_image) {
        // Eliminar la imagen del almacenamiento
        Storage::disk('public')->delete($post->post_image);
    }

    // Eliminar la publicación de la base de datos
    $post->delete();

     session()->flash('message', $post_title. ' Deleted Successfully' );
}



     public function CleanUp()  // FUNCTION CLEAN LIVEWIRE-TMP
    {
   
      $oldfiles= Storage::disk('local');
      foreach ($oldfiles->allFiles('livewire-tmp') as $file)
      {
        $yest=now()->timestamp;
       
        if ($yest > $oldfiles->lastModified($file)) {

            $oldfiles->delete($file);
        }
         
         
      }
  
  }

  
 //---- FUNCTION DELETE MULTIPLE ----//
 public function updatedSelectAll($value)
{
    if ($value) {
        $this->checkedSelected = $this->getItemsIds();
    } else {
        $this->checkedSelected = [];
    }
}

public function getItemsIds()
{
    // Retorna un array con los IDs de los elementos disponibles
    return Post::pluck('id')->toArray();
}


public function confirmDelete()
{
    $this->emit('showConfirmation'); // Emite un evento para mostrar la confirmación
    
}

public function deleteMultiple()
{
    if (count($this->checkedSelected) > 0) {
        // Obtener los posts seleccionados
        $posts = Post::whereIn('id', $this->checkedSelected)->get();
        
        // Eliminar las imágenes asociadas a los posts seleccionados
        foreach ($posts as $post) {
            Storage::disk('public')->delete($post->post_image);
        }
        
        // Eliminar los posts seleccionados
        Post::whereIn('id', $this->checkedSelected)->delete();
        
        // Limpiar la lista de elementos seleccionados
        $this->checkedSelected = [];
        
        // Mostrar mensaje de éxito
        session()->flash('message', 'Data Deleted Successfully');
        
        // Desmarcar la opción de "seleccionar todos"
        $this->selectAll = false;
    }
}


 //---- END FUNCTION DELETE MULTIPLE ----//
}

import os

def list_directory_structure(root_dir, indent_level=0):
    """
    Recursively lists the directory structure starting from the root_dir.

    Parameters:
    root_dir (str): The root directory to start listing from.
    indent_level (int): The current level of indentation for pretty printing.
    """
    try:
        # List all files and directories in the current directory
        items = os.listdir(root_dir)
    except PermissionError:
        # Skip directories that we don't have permission to access
        return

    for item in items:
        item_path = os.path.join(root_dir, item)
        print(' ' * indent_level + '|-- ' + item)

        # If the item is a directory, recursively list its contents
        if os.path.isdir(item_path):
            list_directory_structure(item_path, indent_level + 4)

if __name__ == "__main__":
    root_directory = os.getcwd()  # Use the current working directory
    print(f"Directory structure for {root_directory}:")
    list_directory_structure(root_directory)

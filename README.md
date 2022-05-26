
# Problem Description
> Many operating system distributions (such as various flavors of Linux) manage the installed software so that it is always up-to-date. Much of the software has been modularized into packages to keep things simple and avoid duplicate effort or code. But this means that a package may depend on other packages, requiring their installation before that package can be installed. For example, many programs depend on ‘libc,’ the library of standard C-library functions. To keep track of these dependencies, many distributions use some sort of package manager.
> When a user wants to install a new package (or set of packages), a package manager takes care of the headache of tracking down which packages are required to install the desired package. Of course, each dependency may itself have further dependencies. When the package manager determines all the needed dependencies, it can then install them in an order so that no package is installed before all of its dependencies are installed.
Write a general package manager to take care of this task. Input is a list of packages to install, and all their dependencies. The goal is to give an order of packages that satisfies all dependencies as they are installed. You may assume that, at the beginning, no packages have been installed. Of course, there may be some sets of packages that have circular dependencies; in this case, we assume that none of the packages can be installed.
> Input
> Input consists of up to 10 test cases. Each test case start with a number 1≤n≤1000, which is the number of packages the user wants to install. This is followed by n lines which describe n packages. Each package description starts with the name of the package and is followed by a space-separated list of the package’s unique dependencies. Each package has at most 20 dependencies, and each is one of the other n−1 packages. Each package name is a string of up to 40 non-whitespace characters using the English alphabet (a-z, A-Z), digits (0-9), as well as the characters _, -, ., and +. Input ends when n is zero.
> Output
> For each test case, output the order of package installation that allow them all to be installed with no dependency violations at any point. If there are multiple possible orderings, then give the ordering that is lexicographically first (using ASCII values for string ordering). If there is some group of packages that are not able to be ordered within the list, output ‘cannot be ordered’ instead of ordering the packages. Put a blank line between each pair of test cases.

Sample Input
14
libattr
vim-X11 vim-common gtk2 libattr
vim-common
gtk2 libtiff atk pango glib2
libtiff zlib libjpeg
atk
pango xorg-x11-libs freetype glib2
glib2
zlib
libjpeg
xorg-x11-libs grep freetype
grep pcre
pcre
freetype
3
emacs xorg-x11 lisp
xorg-x11
lisp emacs
0

Sample Output
atk
freetype
glib2
libattr
libjpeg
vim-common
zlib
libtiff
pcre
grep
xorg-x11-libs
pango
gtk2
vim-X11

cannot be ordered



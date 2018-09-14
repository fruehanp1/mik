for file in *.cpd; do
    dir=${file%%.*}
    mkdir -p "$dir"


#    mv "$file" "$dir"
done

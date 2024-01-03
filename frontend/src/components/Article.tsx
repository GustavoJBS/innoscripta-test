type CardProps = {
    title: string;
    navigateUrl: string;
    description?: string;
    imageUrl?: string;
    source: {
        name: string;
        url: string;
    }
};

const Article: React.FC<CardProps> = ({ title, description, imageUrl, navigateUrl }) => {
    return (
        <div className="flex flex-col gap-3 border-2 p-4 border-gray-300">
            {imageUrl && <img className="mx-auto my-3 w-60 h-40 object-cover" src={imageUrl} alt={title} />}

            <a href={navigateUrl} className="hover:opacity-60 duration-300 underline">
                {title}
            </a>

            {description && <p dangerouslySetInnerHTML={{ __html: description }}></p>}
        </div>
    );
};

export default Article;